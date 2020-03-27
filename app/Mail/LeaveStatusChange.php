<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveStatusChange extends Mailable
{
    use Queueable, SerializesModels;
    public $userdata, $admin, $leave;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userdata, $admin, $leave)
    {
        $this->userdata = $userdata;
        $this->admin = $admin;
        $this->leave = $leave;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->admin)->subject('LMSystem App: Leave Application Re-opened')->view('email.leavestatus');
    }
}
