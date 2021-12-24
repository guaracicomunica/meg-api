<?php

namespace App\Jobs;

use App\Mail\ClassroomInvitationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recipients;
    public $template;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipients, $template)
    {
        $this->recipients = $recipients;
        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->recipients)->send($this->template);
    }
}
