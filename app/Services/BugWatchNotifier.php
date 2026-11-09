<?php

namespace App\Services;

use App\Mail\BugStatusChanged;
use App\Models\BugReport;
use App\Models\BugWatcher;
use App\Models\Tenant;
use Illuminate\Support\Facades\Mail;

class BugWatchNotifier
{
    public function statusChanged(BugReport $bug): void
    {
        $tenant = Tenant::current();
        if (! $tenant) {
            return;
        }

        BugWatcher::query()
            ->where('bug_report_id', $bug->id)
            ->whereNotNull('confirmed_at')
            ->orderBy('id')
            ->chunkById(50, function ($rows) use ($bug, $tenant) {
                foreach ($rows as $row) {
                    try {
                        Mail::to($row->email)->send(new BugStatusChanged($bug, $row, $tenant));
                    } catch (\Throwable $e) {
                        report($e);
                    }
                }
            });
    }
}
