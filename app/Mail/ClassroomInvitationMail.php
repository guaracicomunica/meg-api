<?php

namespace App\Mail;

use App\Models\Classroom;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClassroomInvitationMail extends Mailable
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
        $this->classroom = $c;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'name' => $this->classroom->name,
            'code' => $this->classroom->code
        ];

        return $this
            ->view('classrooms.invitation')
            ->with($data);
    }
}
