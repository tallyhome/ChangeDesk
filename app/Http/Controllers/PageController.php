<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\Page;
use App\Models\TodoItem;
use App\Models\Version;
use App\Support\ThemeView;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->first();

        return ThemeView::make('home', compact('page'));
    }

    public function terms()
    {
        $page = Page::where('slug', 'terms')->first();

        return ThemeView::make('terms', compact('page'));
    }

    public function privacy()
    {
        $page = Page::where('slug', 'privacy')->first();

        return ThemeView::make('privacy', compact('page'));
    }

    public function changelog()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();

        return ThemeView::make('changelog', compact('versions'));
    }

    public function todolist()
    {
        $todoItems = TodoItem::orderBy('priority', 'desc')->get();

        return ThemeView::make('todolist', compact('todoItems'));
    }

    public function bugReport()
    {
        $recentBugs = BugReport::orderBy('created_at', 'desc')->take(5)->get();
        $captchaQuestion = $this->ensureBugCaptcha();

        return ThemeView::make('bug-report', compact('recentBugs', 'captchaQuestion'));
    }

    public function storeBugReport(Request $request)
    {
        $this->ensureBugCaptcha();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'captcha' => ['required', 'string', function (string $attribute, mixed $value, \Closure $fail) {
                $expected = (string) session('bug_captcha_sum');
                $given = trim((string) $value);
                if ($expected === '' || ! hash_equals(hash('sha256', $expected), hash('sha256', $given))) {
                    $fail(__('app.public.captcha_invalid'));
                }
            }],
        ]);

        session()->forget(['bug_captcha_sum', 'bug_captcha_label']);

        $bugReport = new BugReport();
        $bugReport->title = strip_tags($validated['title']);
        $bugReport->description = trim(strip_tags($validated['description']));
        $bugReport->status = 'open';
        $bugReport->progress = 0;
        $bugReport->color = 'danger';
        $bugReport->severity = 'medium';
        $bugReport->reporter_name = ! empty($validated['name']) ? $validated['name'] : 'Anonyme';
        $bugReport->reporter_email = ! empty($validated['email']) ? $validated['email'] : 'anonyme@example.com';
        $bugReport->save();

        return redirect()->route('bug-report')->with('success', __('app.flash.bug_public'));
    }

    public function showBugReport($id)
    {
        $bug = BugReport::findOrFail($id);

        return ThemeView::make('bug-report-detail', compact('bug'));
    }

    private function ensureBugCaptcha(): string
    {
        if (! session()->has('bug_captcha_sum')) {
            $a = random_int(2, 9);
            $b = random_int(1, 8);
            session([
                'bug_captcha_sum' => $a + $b,
                'bug_captcha_label' => $a.' + '.$b,
            ]);
        }

        return (string) session('bug_captcha_label');
    }
}
