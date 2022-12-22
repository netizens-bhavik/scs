<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class today_followup_list_mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        echo "<pre>";
//        print_r($this->data);
//        echo "</pre>";
//        exit;

        return $this->view('emails.today_folloup_list_mail')->with('data', $this->data)->subject('Today Followup List');
    }
}
