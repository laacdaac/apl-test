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
        if (isset($this->data['email']))
        {
            $user = User::withTrashed()->updateOrCreate(
                [ "email" => $this->data["email"] ],
                [
                    "name" => isset($this->data["name"]) ? $this->data["name"] : "", 
                    "phone" => isset($this->data["phone"]) ? $this->data["phone"] : "", 
                    "password" => md5($this->data["password"])
                ]
            );
            if ($this->data['deleted'])
            {
                $user->delete();
            }    
        }
        else
        {
            throw new \Exception("Email field is empty");
        }
        
    }
}
