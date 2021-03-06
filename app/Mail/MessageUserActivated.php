<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageUserActivated extends Mailable
{
    use Queueable, SerializesModels;
    public $subject="Alta de registro de usuario";
    public $msg;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg)
    {
        $this->msg=$msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.infoactivation');
    }
    public function handle(MessageSending $event)
    {
        $headers = $event->message->getHeaders();
        $headers->addTextHeader('X-Priority', '1');
		$headers->addTextHeader('X-MSMail-Priority', 'High');
		$headers->addTextHeader('X-Mailer', 'Widgets.com Server');
    }
}
