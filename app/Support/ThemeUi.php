<?php

namespace App\Support;

class ThemeUi
{
    /** @var array<string, string> */
    public const PROGRESS_COLORS = [
        'primary' => '#4f46e5',
        'success' => '#16a34a',
        'info' => '#0ea5e9',
        'warning' => '#f59e0b',
        'danger' => '#ef4444',
        'secondary' => '#64748b',
    ];

    public static function progressColor(?string $color): string
    {
        $key = strtolower((string) $color);

        return self::PROGRESS_COLORS[$key] ?? self::PROGRESS_COLORS['primary'];
    }

    public static function statusLabel(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'pending', 'planned' => __('app.status.pending'),
            'in_progress', 'progress' => __('app.status.in_progress'),
            'completed', 'done' => __('app.status.completed'),
            'resolved' => __('app.status.resolved'),
            'open' => __('app.status.open'),
            'closed' => __('app.status.closed'),
            default => filled($status) ? (string) $status : __('app.status.in_progress'),
        };
    }
}
