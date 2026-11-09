<?php

namespace App\Mail;

use App\Models\ChangelogSubscriber;
use App\Models\Tenant;
use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangelogReleased extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Version $version,
        public ChangelogSubscriber $subscriber,
        public Tenant $tenant,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->plain($this->tenant->name.' '.$this->version->version_number),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.changelog-released');
    }

    private function plain(string $value): string
    {
        return trim(str_replace(["\r", "\n"], ' ', $value));
    }
}
