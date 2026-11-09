<?php

namespace App\Mail;

use App\Models\BugReport;
use App\Models\BugWatcher;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BugStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BugReport $bug,
        public BugWatcher $watcher,
        public Tenant $tenant,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trim(str_replace(["\r", "\n"], ' ', $this->bug->title)),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.bug-status-changed');
    }
}
