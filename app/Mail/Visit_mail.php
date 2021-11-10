<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Visit_mail extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $checkin;
    public $checkout;
    public $visitor;
    public $recipient;
    public $department;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date, $checkin, $checkout, $visitor, $recipient, $department)
    {
        $this->date= $date;
        $this->checkin= $checkin;
        $this->checkout= $checkout;
        $this->visitor= $visitor;
        $this->recipient= $recipient;
        $this->department= $department;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.visit');
    }
}
