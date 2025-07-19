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
        return $this->subject('Selamat Datang di OurKitchen')
                    ->view('mail.account-created')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'password' => $this->password, // Hati-hati dengan mengirim password melalui email
                    ]);
    }
}
