<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage; 
use Tests\TestCase;

class ProcessUserCsvTest extends TestCase
{
    /**
     * Test if command is run with corrupt input file.
     *
     * @return void
     */
    public function testCorruptInputFile()
    {
        Storage::put("users.csv", $this->createTestCsvLineWrongCount());

        $this->artisan('processcsv:users')
            //->expectsOutput('The csv file users.csv is corrupt:')
            ->assertExitCode(1);
    }

    /**
     * Test if command is run with empty input file.
     *
     * @return void
     */
    public function testEmptyInputFile()
    {
        Storage::put("users.csv", "");

        $this->artisan('processcsv:users')
            ->expectsOutput('The csv file users.csv is empty')
            ->assertExitCode(1);
    }

    /**
     * Test if command is run without file name argument.
     *
     * @return void
     */
    public function testNoFileNameArgument()
    {
        Storage::put("users.csv", $this->createTestCsvLine());

        $this->artisan('processcsv:users')
            ->expectsOutput('The csv file users.csv with 1 users successfully queued')
            ->assertExitCode(0);
    }

    /**
     * Test if command is run with file name argument.
     *
     * @return void
     */
    public function testFileNameArgument()
    {
        Storage::put("more_users.csv", $this->createTestCsvLine());

        $this->artisan('processcsv:users more_users.csv')
            ->expectsOutput('The csv file more_users.csv with 1 users successfully queued')
            ->assertExitCode(0);

        Storage::delete("more_users.csv");
    }

    /**
     * Test if command is run with header
     *
     * @return void
     */
    public function testRunWithHeaderOption()
    {
        Storage::put("more_users.csv", $this->createTestCsvLine(1));

        $this->artisan('processcsv:users more_users.csv --with-headers')
            ->expectsOutput('The csv file more_users.csv with 1 users successfully queued')
            ->assertExitCode(0);

        Storage::delete("more_users.csv");
    }

    /**
     * Create test csv line for users import
     *
     * @return string
     */
    private function createTestCsvLine(int $withHeader = 0)
    {
        return ($withHeader ? '"name","email","phone","password","deleted"'."\n" : '').'"Luka László","laszlo.luka@prowebshop.ro","+40733307722","password","0"';
    }

    /**
     * Create corrupt test csv line for users import
     *
     * @return string
     */
    private function createTestCsvLineWrongCount(int $withHeader = 0)
    {
        return ($withHeader ? '"name","email","phone","password"'."\n" : '').'"Luka László","laszlo.luka@prowebshop.ro","+40733307722","password"';
    }

    
}
