<?php

namespace App\Services;

use App\Mail\SubscriptionConfirm;
use App\Models\BugReport;
use App\Models\BugWatcher;
use App\Models\ChangelogSubscriber;
use App\Models\Tenant;
use Illuminate\Support\Facades\Mail;

class PublicSubscription
{
    public function requestChangelog(string $email): void
    {
        $tenant = $this->tenant();
        $email = $this->email($email);
        $row = ChangelogSubscriber::query()->where('email', $email)->first();

        if ($row && $row->confirmed_at) {
            return;
        }

        if (! $row) {
            $row = new ChangelogSubscriber();
            $row->email = $email;
        }

        $row->token = $this->token();
        $row->confirmed_at = null;

        try {
            $row->save();
        } catch (\Illuminate\Database\QueryException) {
            return;
        }

        $this->sendConfirm($email, $tenant, $tenant->publicBaseUrl().'/changelog/subscribe/'.$row->token);
    }

    public function confirmChangelog(string $token): void
    {
        $row = ChangelogSubscriber::query()->where('token', $this->assertToken($token))->firstOrFail();
        if (! $row->confirmed_at) {
            $row->confirmed_at = now();
            $row->save();
        }
    }

    public function unsubscribeChangelog(string $token): void
    {
        ChangelogSubscriber::query()->where('token', $this->assertToken($token))->delete();
    }

    public function requestBug(BugReport $bug, string $email): void
    {
        $tenant = $this->tenant();
        $email = $this->email($email);
        $row = BugWatcher::query()
            ->where('bug_report_id', $bug->id)
            ->where('email', $email)
            ->first();

        if ($row && $row->confirmed_at) {
            return;
        }

        if (! $row) {
            $row = new BugWatcher();
            $row->bug_report_id = $bug->id;
            $row->email = $email;
        }

        $row->token = $this->token();
        $row->confirmed_at = null;

        try {
            $row->save();
        } catch (\Illuminate\Database\QueryException) {
            return;
        }

        $this->sendConfirm($email, $tenant, $tenant->publicBaseUrl().'/bug-watch/'.$row->token);
    }

    public function confirmBug(string $token): void
    {
        $row = BugWatcher::query()->where('token', $this->assertToken($token))->firstOrFail();
        if (! $row->confirmed_at) {
            $row->confirmed_at = now();
            $row->save();
        }
    }

    public function unsubscribeBug(string $token): void
    {
        BugWatcher::query()->where('token', $this->assertToken($token))->delete();
    }

    private function sendConfirm(string $email, Tenant $tenant, string $url): void
    {
        try {
            Mail::to($email)->send(new SubscriptionConfirm($tenant, $url, 'confirm'));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function tenant(): Tenant
    {
        $tenant = Tenant::current();
        abort_unless($tenant, 404);

        return $tenant;
    }

    private function email(string $email): string
    {
        return strtolower(trim($email));
    }

    private function token(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function assertToken(string $token): string
    {
        abort_unless(preg_match('/^[a-f0-9]{64}$/', $token) === 1, 404);

        return $token;
    }
}
