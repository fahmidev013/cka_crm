<?php

namespace App\Jobs;

use App\Http\Controllers\EmailController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    public function __construct($data)
    {
        $this->data  = $data; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new EmailController)->sendEmail($this->data['email'],$this->data['subject'],$this->data['message']);
    }
}
