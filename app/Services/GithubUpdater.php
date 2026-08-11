<?php

namespace App\Services;

use App\Support\GithubUpdateAuth;
use App\Support\VersionComparator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class GithubUpdater
{
    public function __construct(
        protected ?VersionComparator $versions = null,
        protected ?UpdateProgress $progress = null,
    ) {
        $this->versions ??= new VersionComparator;
        $this->progress ??= new UpdateProgress;
    }

    public function currentVersion(): string
    {
        return $this->versions->normalize((string) config('updates.number', config('version.number', '0.0.0')));
    }

    public function latestRelease(bool $fresh = false): ?array
    {
        $cacheKey = 'chanlog:github:latest-release:'.md5(GithubUpdateAuth::REPO);

        if ($fresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addMinutes(10), fn () => $this->fetchLatestRelease());
    }

    protected function fetchLatestRelease(): ?array
    {
        $repo = GithubUpdateAuth::REPO;
        $base = rtrim(GithubUpdateAuth::API, '/');

        $response = Http::withHeaders($this->headers())
            ->timeout(25)
            ->get("{$base}/repos/{$repo}/releases/latest");

        if ($response->successful()) {
            $parsed = $this->parseReleasePayload($response->json());
            if ($parsed) {
                return $parsed;
            }
        }

        $list = Http::withHeaders($this->headers())
            ->timeout(25)
            ->get("{$base}/repos/{$repo}/releases", ['per_page' => 10]);

        if ($list->successful()) {
            foreach ($list->json() as $item) {
                if (! empty($item['draft']) || ! empty($item['prerelease'])) {
                    continue;
                }
                $parsed = $this->parseReleasePayload($item);
                if ($parsed) {
                    return $parsed;
                }
            }
        }

        $tags = Http::withHeaders($this->headers())
            ->timeout(25)
            ->get("{$base}/repos/{$repo}/tags", ['per_page' => 10]);

        if ($tags->successful()) {
            foreach ($tags->json() as $tag) {
                $name = $this->versions->normalize((string) ($tag['name'] ?? ''));
                if ($name === '') {
                    continue;
                }

                return [
                    'tag' => $name,
                    'name' => $name,
                    'body' => '',
                    'url' => 'https://github.com/'.$repo.'/releases/tag/v'.$name,
                    'zipball' => $tag['zipball_url'] ?? "{$base}/repos/{$repo}/zipball/{$tag['name']}",
                    'asset_zip' => null,
                    'published_at' => null,
                    'prerelease' => false,
                ];
            }
        }

        if ($response->status() === 404 && ! GithubUpdateAuth::hasToken()) {
            throw new RuntimeException(
                'Release introuvable (404). Si le dépôt est privé, renseigne un PAT lecture seule dans App\\Support\\GithubUpdateAuth::TOKEN.'
            );
        }

        throw new RuntimeException('GitHub API : aucune release/tag utilisable ('.$response->status().').');
    }

    protected function parseReleasePayload(?array $json): ?array
    {
        if (! $json) {
            return null;
        }

        $tag = $this->versions->normalize((string) ($json['tag_name'] ?? ''));
        if ($tag === '') {
            return null;
        }

        $assetZip = null;
        foreach ($json['assets'] ?? [] as $asset) {
            $name = strtolower((string) ($asset['name'] ?? ''));
            if (str_ends_with($name, '.zip') && ! empty($asset['url'])) {
                $assetZip = $asset['url'];
                break;
            }
        }

        return [
            'tag' => $tag,
            'name' => $json['name'] ?? $tag,
            'body' => $json['body'] ?? '',
            'url' => $json['html_url'] ?? null,
            'zipball' => $json['zipball_url'] ?? null,
            'asset_zip' => $assetZip,
            'published_at' => $json['published_at'] ?? null,
            'prerelease' => (bool) ($json['prerelease'] ?? false),
        ];
    }

    public function isUpdateAvailable(?array $release = null): bool
    {
        $release ??= $this->latestRelease();
        if (! $release || empty($release['tag'])) {
            return false;
        }

        return $this->versions->isNewer($release['tag'], $this->currentVersion());
    }

    /**
     * @return array{from:string,to:string,commands:array<int,array{cmd:string,ok:bool,output?:string}>}
     */
    public function apply(array $release): array
    {
        $from = $this->currentVersion();
        $to = $this->versions->normalize($release['tag'] ?? '');

        $this->progress->start($from, $to);

        try {
            if (! $this->versions->isNewer($to, $from)) {
                throw new RuntimeException('Refus de downgrade / même version.');
            }
            if (! class_exists(ZipArchive::class)) {
                throw new RuntimeException('Extension PHP zip requise.');
            }

            $this->progress->step(8, 'Préparation', 'Création du dossier temporaire');
            $tmpRoot = storage_path('app/updates/'.Str::random(8));
            File::ensureDirectoryExists($tmpRoot);
            $zipPath = $tmpRoot.'/release.zip';

            $this->progress->step(18, 'Téléchargement', 'Récupération de la release GitHub…');
            $this->downloadReleaseArchive($release, $zipPath, function (string $msg) {
                $this->progress->step(30, 'Téléchargement', $msg);
            });

            if (! File::exists($zipPath) || File::size($zipPath) < 1000) {
                throw new RuntimeException('Téléchargement de la release impossible.');
            }

            $sizeMb = round(File::size($zipPath) / 1048576, 2);
            $this->progress->step(42, 'Extraction', "Archive {$sizeMb} Mo");

            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== true) {
                throw new RuntimeException('Archive ZIP invalide.');
            }
            $extractTo = $tmpRoot.'/extract';
            File::ensureDirectoryExists($extractTo);
            $zip->extractTo($extractTo);
            $count = $zip->numFiles;
            $zip->close();

            $this->progress->step(55, 'Extraction', "{$count} fichiers extraits");
            $payload = $this->resolvePayloadRoot($extractTo);

            $this->progress->step(60, 'Installation des fichiers', 'Copie vers l’application…');
            $copied = $this->mirror($payload, base_path(), function (int $done, int $total) {
                $pct = 60 + (int) floor(($done / max(1, $total)) * 12);
                $this->progress->step($pct, 'Installation des fichiers', "{$done}/{$total}");
            });

            File::deleteDirectory($tmpRoot);
            $this->progress->step(74, 'Version', "Écriture v{$to}");
            $this->writeVersion($to);
            Cache::forget('chanlog:github:latest-release:'.md5(GithubUpdateAuth::REPO));

            $this->progress->step(78, 'Post-déploiement', 'Commandes automatiques…');
            $commands = $this->runPostDeployCommands();

            $result = [
                'from' => $from,
                'to' => $to,
                'files_copied' => $copied,
                'commands' => $commands,
            ];
            $this->progress->complete($result);

            return $result;
        } catch (\Throwable $e) {
            $this->progress->fail($e->getMessage());
            throw $e;
        }
    }

    /**
     * @return array<int, array{cmd:string,ok:bool,output:string}>
     */
    protected function runPostDeployCommands(): array
    {
        $steps = [
            ['migrate --force', fn () => Artisan::call('migrate', ['--force' => true]), 80],
            ['optimize:clear', fn () => Artisan::call('optimize:clear'), 85],
            ['storage:link', fn () => Artisan::call('storage:link'), 88],
            ['config:cache', fn () => Artisan::call('config:cache'), 91],
            ['route:cache', fn () => Artisan::call('route:cache'), 94],
            ['view:cache', fn () => Artisan::call('view:cache'), 97],
        ];

        $results = [];
        foreach ($steps as [$label, $fn, $pct]) {
            $this->progress->step($pct, 'Post-déploiement', $label);
            try {
                $fn();
                $out = trim(Artisan::output());
                $results[] = ['cmd' => $label, 'ok' => true, 'output' => Str::limit($out, 400)];
            } catch (\Throwable $e) {
                // storage:link / caches peuvent échouer sans bloquer
                $results[] = ['cmd' => $label, 'ok' => false, 'output' => $e->getMessage()];
                $this->progress->step($pct, 'Post-déploiement', $label.' (avertissement)');
            }
        }

        return $results;
    }

    protected function downloadReleaseArchive(array $release, string $zipPath, ?callable $onProgress = null): void
    {
        if (! empty($release['asset_zip'])) {
            $onProgress && $onProgress('Asset ZIP de la release');
            $this->downloadAuthenticated($release['asset_zip'], $zipPath, [
                'Accept' => 'application/octet-stream',
            ]);

            return;
        }

        if (empty($release['zipball'])) {
            throw new RuntimeException('URL archive manquante (ni asset zip, ni zipball).');
        }

        $onProgress && $onProgress('Zipball GitHub');
        $this->downloadAuthenticated($release['zipball'], $zipPath, [
            'Accept' => 'application/vnd.github+json',
        ]);
    }

    protected function downloadAuthenticated(string $url, string $destination, array $extraHeaders = []): void
    {
        $response = Http::withHeaders(array_merge($this->headers(), $extraHeaders))
            ->withOptions(['allow_redirects' => false])
            ->timeout(180)
            ->get($url);

        if (in_array($response->status(), [301, 302, 303, 307, 308], true)) {
            $location = $response->header('Location');
            if (! $location) {
                throw new RuntimeException('Redirect GitHub sans Location.');
            }

            $file = Http::withHeaders(['User-Agent' => 'ChanLog-Updater'])
                ->timeout(240)
                ->sink($destination)
                ->get($location);

            if (! $file->successful()) {
                throw new RuntimeException('Téléchargement codeload échoué : '.$file->status());
            }

            return;
        }

        if (! $response->successful()) {
            throw new RuntimeException('Téléchargement GitHub échoué : '.$response->status());
        }

        File::put($destination, $response->body());
    }

    protected function resolvePayloadRoot(string $extractTo): string
    {
        $dirs = File::directories($extractTo);
        if (count($dirs) === 1 && ! File::exists($extractTo.'/artisan')) {
            return $dirs[0];
        }

        if (File::exists($extractTo.'/artisan') || File::exists($extractTo.'/composer.json')) {
            return $extractTo;
        }

        if (count($dirs) >= 1) {
            return $dirs[0];
        }

        throw new RuntimeException('Structure archive GitHub inattendue.');
    }

    protected function headers(): array
    {
        $headers = [
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'ChanLog-Updater',
            'X-GitHub-Api-Version' => '2022-11-28',
        ];

        if (GithubUpdateAuth::hasToken()) {
            $headers['Authorization'] = 'Bearer '.GithubUpdateAuth::token();
        }

        return $headers;
    }

    protected function mirror(string $source, string $destination, ?callable $onProgress = null): int
    {
        $preserve = collect(config('updates.preserve', []))
            ->map(fn ($p) => str_replace('\\', '/', $p))
            ->all();

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($source) + 1));
            if ($relative === '' || $this->isPreserved($relative, $preserve)) {
                continue;
            }
            if (str_starts_with($relative, 'vendor/') || $relative === 'vendor'
                || str_starts_with($relative, 'node_modules/') || $relative === 'node_modules') {
                continue;
            }
            $files[] = [$file, $relative];
        }

        $total = count($files);
        $done = 0;
        foreach ($files as [$file, $relative]) {
            $target = $destination.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if ($file->isDir()) {
                File::ensureDirectoryExists($target);
            } else {
                File::ensureDirectoryExists(dirname($target));
                File::copy($file->getPathname(), $target);
            }
            $done++;
            if ($onProgress && ($done % 25 === 0 || $done === $total)) {
                $onProgress($done, $total);
            }
        }

        return $done;
    }

    protected function isPreserved(string $relative, array $preserve): bool
    {
        foreach ($preserve as $path) {
            if ($relative === $path || str_starts_with($relative, rtrim($path, '/').'/')) {
                return true;
            }
        }

        return false;
    }

    protected function writeVersion(string $version): void
    {
        foreach (['updates.php', 'version.php'] as $file) {
            $path = config_path($file);
            if (! File::exists($path)) {
                continue;
            }

            $contents = File::get($path);
            $updated = preg_replace(
                "/('number'\\s*=>\\s*')([^']*)(')/",
                '${1}'.$version.'${3}',
                $contents,
                1
            );

            if (is_string($updated) && $updated !== $contents) {
                File::put($path, $updated);
            }
        }

        config(['updates.number' => $version, 'version.number' => $version]);
    }
}
