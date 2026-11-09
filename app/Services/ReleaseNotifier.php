<?php

namespace App\Services;

use App\Mail\ChangelogReleased;
use App\Models\ChangelogSubscriber;
use App\Models\Tenant;
use App\Models\Version;
use Illuminate\Support\Facades\Mail;

class ReleaseNotifier
{
    public function created(Version $version): void
    {
        $tenant = Tenant::current();
        if (! $tenant) {
            return;
        }

        ChangelogSubscriber::query()
            ->whereNotNull('confirmed_at')
            ->orderBy('id')
            ->chunkById(50, function ($rows) use ($version, $tenant) {
                foreach ($rows as $row) {
                    $this->send($version, $row, $tenant);
                }
            });
    }

    private function send(Version $version, ChangelogSubscriber $row, Tenant $tenant): void
    {
        try {
            Mail::to($row->email)->send(new ChangelogReleased($version, $row, $tenant));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
