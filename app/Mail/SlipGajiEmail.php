<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SlipGajiEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $filename;
    public $item;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item, $filename)
    {
        $this->filename = $filename;
        $this->item = $item;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Slip Gaji Notification',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.send_slip_gaji',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromStorageDisk('s3', $this->filename)
                ->as($this->item->period_payroll . '-' . $this->item->employeement_id)
                ->withMime('application/pdf'),
        ];
    }
}
