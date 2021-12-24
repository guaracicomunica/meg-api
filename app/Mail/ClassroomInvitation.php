<?php

namespace App\Mail;

use App\Models\Classroom;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClassroomInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $classroom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Classroom $c)
    {
        $classroom = $c;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('classrooms.invitation');
    }
}
