<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreatePasswordEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $createPassword;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($createPassword)
    {
        $this->createPassword = $createPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('AAS Customer Portal Access-Confirm Your Email Address')
                    ->view('mails.createPassword');
        // return $this->view('view.name');
    }
}
