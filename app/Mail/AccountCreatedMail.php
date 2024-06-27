<?php

namespace App\Mail;

use App\Models\GeneralSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $email, $password;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */

     public function build()
    {
        $generalSetting = GeneralSetting::first();
        return $this->subject('Selamat Datang di '. $generalSetting->site_name)
                    ->view('mail.account-created')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'password' => $this->password, // Hati-hati dengan mengirim password melalui email
                    ]);
    }

    // public function envelope(): Envelope
    // {
    //     $generalSetting = GeneralSetting::first();

    //     return new Envelope(
    //         subject: 'Welcome to '. $generalSetting->site_name,
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'Mail.account-created',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
