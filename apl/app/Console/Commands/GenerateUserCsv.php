<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage; 

use Faker\Generator;

class GenerateUserCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generatecsv:users {recordcount} {filename?} {--with-headers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CSV file with user data (name, email, password and phone number)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $faker = \Faker\Factory::create();

        $lines = array();

        if ($this->option("with-headers"))
        {
            array_push($lines, str_putcsv(["name", "email", "phone", "password", "deleted"]));   
        }

        for ($i = 0; $i < $this->argument('recordcount'); $i++)
        {
            $sampleUser = $this->createSampleUser($faker);
            
            array_push($lines, str_putcsv($sampleUser));
            
        }

        Storage::put($this->getCsvFileName(), join("\n", $lines));

        $this->info('The csv file ' . $this->getCsvFileName() . ' with ' . $this->argument('recordcount') . ' users was successfully generated');

        return 0;
    }

    /**
     * Returns the file name where to save csv data
     *
     * @return string
     */
    private function getCsvFileName()
    {
        return (config("apl.output_directory") ? config("apl.output_directory"). "/" : "").($this->argument('filename') ? $this->argument('filename') : config('apl.users_filename'));
    }
    
    /**
     * Creates a sample user with Faker data
     *
     * @return User
     */
    private function createSampleUser(Generator $faker)
    {
        return [
            "name" => $faker->firstName . " " . $faker->lastName,
            "email" => $faker->unique()->safeEmail,
            "phone" => $faker->phoneNumber,
            "password" => urlencode($faker->password),
            "deleted" => $faker->randomDigit == 8 ? 1 : 0
        ];
    }
}
