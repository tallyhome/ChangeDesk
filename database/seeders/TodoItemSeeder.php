<?php

namespace Database\Seeders;

use App\Models\TodoItem;
use Illuminate\Database\Seeder;

class TodoItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TodoItem::create([
            'title' => 'Système de messagerie instantanée',
            'description' => 'Implémentation d\'un système de chat en temps réel entre les utilisateurs de la plateforme.',
            'priority' => 5,
            'completion_percentage' => 75,
            'estimated_completion_date' => now()->addDays(15),
        ]);

        TodoItem::create([
            'title' => 'Interface mobile responsive',
            'description' => 'Adaptation complète de l\'interface pour les appareils mobiles et tablettes.',
            'priority' => 4,
            'completion_percentage' => 90,
            'estimated_completion_date' => now()->addDays(5),
        ]);

        TodoItem::create([
            'title' => 'Système de notifications',
            'description' => 'Mise en place d\'un système de notifications pour informer les utilisateurs des mises à jour et des événements importants.',
            'priority' => 3,
            'completion_percentage' => 30,
            'estimated_completion_date' => now()->addDays(30),
        ]);

        TodoItem::create([
            'title' => 'Intégration des paiements en ligne',
            'description' => 'Ajout de la possibilité d\'effectuer des paiements en ligne via Stripe et PayPal.',
            'priority' => 4,
            'completion_percentage' => 15,
            'estimated_completion_date' => now()->addDays(45),
        ]);

        TodoItem::create([
            'title' => 'Système d\'authentification avancé',
            'description' => 'Mise en place de l\'authentification à deux facteurs et de la connexion via réseaux sociaux.',
            'priority' => 5,
            'completion_percentage' => 60,
            'estimated_completion_date' => now()->addDays(20),
        ]);
    }
}
