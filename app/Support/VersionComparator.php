<?php

namespace App\Support;

final class VersionComparator
{
    public function isNewer(string $remote, string $current): bool
    {
        return version_compare(ltrim($remote, 'v'), ltrim($current, 'v'), '>');
    }

    public function normalize(string $version): string
    {
        return ltrim(trim($version), 'v');
    }
}
