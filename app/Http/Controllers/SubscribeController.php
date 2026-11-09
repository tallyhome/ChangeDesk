<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Services\PublicSubscription;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function __construct(private PublicSubscription $subscriptions) {}

    public function changelog(Request $request)
    {
        if (filled($request->input('evolora_hp'))) {
            return back()->with('success', __('app.public.subscribe_sent'));
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $this->subscriptions->requestChangelog($data['email']);

        return back()->with('success', __('app.public.subscribe_sent'));
    }

    public function confirmChangelog(string $token)
    {
        $this->subscriptions->confirmChangelog($token);

        return redirect()->route('changelog')->with('success', __('app.public.subscribe_confirmed'));
    }

    public function unsubscribeChangelog(string $token)
    {
        $this->subscriptions->unsubscribeChangelog($token);

        return redirect()->route('changelog')->with('success', __('app.public.unsubscribed'));
    }

    public function bug(Request $request, $id)
    {
        if (filled($request->input('evolora_hp'))) {
            return back()->with('success', __('app.public.subscribe_sent'));
        }

        $bug = BugReport::query()->findOrFail($id);
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $this->subscriptions->requestBug($bug, $data['email']);

        return back()->with('success', __('app.public.subscribe_sent'));
    }

    public function confirmBug(string $token)
    {
        $this->subscriptions->confirmBug($token);

        return redirect()->route('bug-report')->with('success', __('app.public.subscribe_confirmed'));
    }

    public function unsubscribeBug(string $token)
    {
        $this->subscriptions->unsubscribeBug($token);

        return redirect()->route('bug-report')->with('success', __('app.public.unsubscribed'));
    }
}
