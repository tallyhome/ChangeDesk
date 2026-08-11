<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class UpdateProgress
{
    protected function path(): string
    {
        return storage_path('app/updates/progress.json');
    }

    public function start(string $from, string $to): void
    {
        File::ensureDirectoryExists(dirname($this->path()));
        $this->write([
            'status' => 'running',
            'percent' => 1,
            'step' => 'Démarrage',
            'detail' => "v{$from} → v{$to}",
            'from' => $from,
            'to' => $to,
            'logs' => [['t' => now()->toDateTimeString(), 'm' => 'Mise à jour démarrée']],
            'error' => null,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function step(int $percent, string $step, ?string $detail = null): void
    {
        $data = $this->read();
        $data['status'] = 'running';
        $data['percent'] = max(0, min(99, $percent));
        $data['step'] = $step;
        $data['detail'] = $detail;
        $data['logs'][] = ['t' => now()->toDateTimeString(), 'm' => trim($step.($detail ? ' — '.$detail : ''))];
        $data['logs'] = array_slice($data['logs'] ?? [], -40);
        $data['updated_at'] = now()->toIso8601String();
        $this->write($data);
    }

    public function fail(string $message): void
    {
        $data = $this->read();
        $data['status'] = 'failed';
        $data['percent'] = $data['percent'] ?? 0;
        $data['step'] = 'Échec';
        $data['detail'] = $message;
        $data['error'] = $message;
        $data['logs'][] = ['t' => now()->toDateTimeString(), 'm' => 'ERREUR : '.$message];
        $data['updated_at'] = now()->toIso8601String();
        $this->write($data);
    }

    public function complete(array $result = []): void
    {
        $data = $this->read();
        $data['status'] = 'done';
        $data['percent'] = 100;
        $data['step'] = 'Terminé';
        $data['detail'] = 'Mise à jour appliquée avec succès';
        $data['error'] = null;
        $data['result'] = $result;
        $data['logs'][] = ['t' => now()->toDateTimeString(), 'm' => 'Mise à jour terminée'];
        $data['updated_at'] = now()->toIso8601String();
        $this->write($data);
    }

    public function read(): array
    {
        if (! File::exists($this->path())) {
            return [
                'status' => 'idle',
                'percent' => 0,
                'step' => null,
                'detail' => null,
                'logs' => [],
                'error' => null,
            ];
        }

        $json = json_decode(File::get($this->path()), true);

        return is_array($json) ? $json : ['status' => 'idle', 'percent' => 0, 'logs' => []];
    }

    protected function write(array $data): void
    {
        File::put($this->path(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
