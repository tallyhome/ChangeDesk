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
            'pending', 'planned' => 'En attente',
            'in_progress', 'progress' => 'En cours',
            'completed', 'done', 'resolved' => 'Terminé',
            'open' => 'Ouvert',
            'closed' => 'Fermé',
            default => filled($status) ? (string) $status : 'En cours',
        };
    }
}
