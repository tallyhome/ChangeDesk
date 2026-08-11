<?php

namespace App\Services;

use App\Models\Tenant;

class DomainVerificationService
{
    public function verify(Tenant $tenant): bool
    {
        $domain = strtolower(trim((string) $tenant->custom_domain));
        if ($domain === '') {
            return false;
        }

        $expected = strtolower(rtrim((string) config('tenancy.cname_target'), '.'));
        $token = $tenant->ensureVerificationToken();

        $cnameOk = $this->cnamePointsTo($domain, $expected);
        $txtOk = $this->hasVerificationTxt($domain, $token);

        if ($cnameOk || $txtOk) {
            $tenant->update(['domain_status' => Tenant::DOMAIN_VERIFIED]);

            return true;
        }

        $tenant->update(['domain_status' => Tenant::DOMAIN_PENDING]);

        return false;
    }

    protected function cnamePointsTo(string $domain, string $expected): bool
    {
        $records = @dns_get_record($domain, DNS_CNAME) ?: [];

        foreach ($records as $record) {
            $target = strtolower(rtrim($record['target'] ?? '', '.'));
            if ($target === $expected || str_ends_with($target, '.'.$expected)) {
                return true;
            }
        }

        return false;
    }

    protected function hasVerificationTxt(string $domain, string $token): bool
    {
        $host = '_changedesk-challenge.'.$domain;
        $records = @dns_get_record($host, DNS_TXT) ?: [];
        $expected = 'changedesk-verify='.$token;

        foreach ($records as $record) {
            $txt = $record['txt'] ?? '';
            if (is_array($txt)) {
                $txt = implode('', $txt);
            }
            if (trim($txt) === $expected) {
                return true;
            }
        }

        return false;
    }
}
