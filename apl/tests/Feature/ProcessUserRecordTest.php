<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Jobs\ProcessUserRecord;
use App\Models\User;

class ProcessUserRecordTest extends TestCase
{
    /**
     * Test worker inserts the record in database
     *
     * @return void
     */
    public function testWorkerPersistsUserToDatabase()
    {
        $user = User::factory()->make();
        $data = array(
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "password" => $user->password,
            "deleted" => 0
        );
        
        ProcessUserRecord::dispatch($data)->onQueue('userstest');

        $this->artisan('queue:work --queue=userstest --once')
            //->expectsOutput('The csv file users.csv is corrupt')
            ->assertExitCode(0);

        $this->assertDatabaseHas("users", array(
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "password" => md5($user->password),
        ));
    }

    /**
     * Test worker persists the record in database for a soft deleted user
     *
     * @return void
     */
    public function testWorkerPersistsDeletedUserToDatabase()
    {
        $user = User::factory()->make();
        $data = array(
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "password" => $user->password,
            "deleted" => 1
        );
        
        ProcessUserRecord::dispatch($data)->onQueue('userstest');

        $this->artisan('queue:work --queue=userstest --once')
            //->expectsOutput('The csv file users.csv is corrupt')
            ->assertExitCode(0);
        
        $expected = array(
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "password" => md5($user->password),
        );

        $this->assertDatabaseHas("users", $expected);

        $this->assertSoftDeleted("users", $expected);
    }

}
