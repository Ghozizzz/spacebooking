<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserBooking;

class CancelBooking extends Mailable
{
    use Queueable, SerializesModels;

    public $userBooking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserBooking $userBooking)
    {
        //
        $this->userBooking = $userBooking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = env('MAIL_FROM_ADDRESS', 'spacebooking@uph.edu');
        return $this->from($from)
        ->subject('Your booking has been cancelled')
        ->view('mail.booking');
    }
}
