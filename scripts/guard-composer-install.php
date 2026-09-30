<?php

/**
 * Empêche, quand APP_ENV=production, de réinstaller un vendor/ énorme.
 *
 * - avant l'install : refuse les dépendances de dev (PHPUnit, Sebastian, …) ;
 * - après chaque paquet et à la fin : refuse les dépôts Git laissés dans vendor/.
 *
 * En local (APP_ENV absent ou différent de production), le script ne fait rien.
 * Il ne supprime aucun fichier.
 */

$phase = $argv[1] ?? 'pre';
$root = dirname(__DIR__);
$env = appEnv($root);

if ($env !== 'production') {
    exit(0);
}

if (composerDevMode()) {
    fwrite(STDERR, productionDevModeMessage());
    exit(1);
}

if ($phase === 'pre') {
    exit(0);
}

$gitDirs = vendorGitDirs($root.'/vendor');
if ($gitDirs !== []) {
    fwrite(STDERR, productionGitMessage($gitDirs));
    exit(1);
}

exit(0);

function composerDevMode(): bool
{
    $value = getenv('COMPOSER_DEV_MODE');
    if ($value === false || $value === '') {
        return false;
    }

    return $value === '1';
}

function appEnv(string $root): ?string
{
    $fromProcess = getenv('APP_ENV');
    if (is_string($fromProcess) && $fromProcess !== '') {
        return $fromProcess;
    }

    $path = $root.DIRECTORY_SEPARATOR.'.env';
    if (! is_file($path)) {
        return null;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return null;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_starts_with($line, 'export ')) {
            $line = trim(substr($line, 7));
        }
        if (! str_starts_with($line, 'APP_ENV=')) {
            continue;
        }

        $value = trim(substr($line, strlen('APP_ENV=')));
        if ($value !== '' && ($value[0] === '"' || $value[0] === "'")) {
            $value = trim($value, "\"'");
        }

        return $value !== '' ? $value : null;
    }

    return null;
}

/**
 * @return list<string>
 */
function vendorGitDirs(string $vendor): array
{
    if (! is_dir($vendor)) {
        return [];
    }

    $found = [];
    $vendors = scandir($vendor);
    if ($vendors === false) {
        return [];
    }

    foreach ($vendors as $vendorName) {
        if ($vendorName === '.' || $vendorName === '..') {
            continue;
        }

        $vendorPath = $vendor.DIRECTORY_SEPARATOR.$vendorName;
        if (! is_dir($vendorPath)) {
            continue;
        }

        if (isSourceCheckout($vendorPath)) {
            $found[] = 'vendor/'.$vendorName;
        }

        $packages = scandir($vendorPath);
        if ($packages === false) {
            continue;
        }

        foreach ($packages as $packageName) {
            if ($packageName === '.' || $packageName === '..') {
                continue;
            }

            $packagePath = $vendorPath.DIRECTORY_SEPARATOR.$packageName;
            if (is_dir($packagePath) && isSourceCheckout($packagePath)) {
                $found[] = 'vendor/'.$vendorName.'/'.$packageName;
            }
        }
    }

    return $found;
}

function isSourceCheckout(string $path): bool
{
    $git = $path.DIRECTORY_SEPARATOR.'.git';

    return is_dir($git) || is_file($git);
}

function productionDevModeMessage(): string
{
    return <<<'TXT'

Installation interrompue.

APP_ENV=production : les dépendances de développement ne doivent pas être installées.
PHPUnit, Sebastian et les paquets associés ont déjà produit un vendor/ de plusieurs Go,
parce que Composer les clone en dépôts Git quand l'archive zip échoue.

Relancez exactement :

  composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

TXT;
}

/**
 * @param list<string> $gitDirs
 */
function productionGitMessage(array $gitDirs): string
{
    $shown = array_slice($gitDirs, 0, 20);
    $list = implode("\n", array_map(static fn (string $path): string => '  - '.$path, $shown));
    $extra = count($gitDirs) > count($shown)
        ? "\n  … et ".(count($gitDirs) - count($shown))." autre(s)"
        : '';

    return <<<TXT

Installation interrompue.

vendor/ contient des dépôts Git. Composer a cloné des sources au lieu d'utiliser les archives dist :
{$list}{$extra}

Supprimez le dossier vendor/, puis relancez :

  composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

Si la sortie affiche « trying to download from source » ou « Cloning », le zip a échoué
(souvent une limite de l'API GitHub). Corrigez le réseau, ou enregistrez un token en lecture seule
hors du dépôt :

  composer config --auth github-oauth.github.com VOTRE_TOKEN

Ne supprimez pas seulement les dossiers .git : le clone reste plus gros qu'une archive dist.

TXT;
}
