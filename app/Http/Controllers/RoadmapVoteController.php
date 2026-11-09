<?php

namespace App\Http\Controllers;

use App\Models\RoadmapVote;
use App\Models\TodoItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class RoadmapVoteController extends Controller
{
    public function toggle(Request $request, TodoItem $todoItem)
    {
        $token = (string) $request->cookie('roadmap_voter');
        $fresh = preg_match('/^[a-f0-9]{32}$/', $token) !== 1;
        if ($fresh) {
            $token = bin2hex(random_bytes(16));
        }

        $existing = RoadmapVote::query()
            ->where('todo_item_id', $todoItem->id)
            ->where('voter_token', $token)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            $vote = new RoadmapVote();
            $vote->todo_item_id = $todoItem->id;
            $vote->voter_token = $token;
            try {
                $vote->save();
            } catch (\Illuminate\Database\QueryException) {
                // Un second clic simultané a déjà enregistré ce vote.
            }
        }

        return back()->withCookie($this->voterCookie($token));
    }

    private function voterCookie(string $token): Cookie
    {
        return cookie(
            'roadmap_voter',
            $token,
            60 * 24 * 400,
            '/',
            null,
            (bool) config('session.secure'),
            true,
            false,
            'lax'
        );
    }
}
