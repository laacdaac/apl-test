<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class ProcessUserRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        //Store data from csv record 
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Create user in database or update previous
        $user = User::withTrashed()->updateOrCreate(
            [ "email" => $this->data["email"] ],
            [
                "name" => $this->data["name"], 
                "phone" => $this->data["phone"], 
                "password" => md5($this->data["password"])
            ]
        );
        if ($this->data['deleted'])
        {
            $user->delete();
        }
        
    }
}
