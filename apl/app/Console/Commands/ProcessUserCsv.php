<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage; 

use App\Models\User;
use App\Jobs\ProcessUserRecord;

class ProcessUserCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processcsv:users {filename=users.csv} {--with-headers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' Process given CSV file and insert the records into the User Importing Queue';

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
        $userCsvFileContent = $this->getUserCsvFileContent();

        //if (!$this->validate()) return 1;
        
        if ($userCsvFileContent)
        {
            $csvLines = explode("\n", $userCsvFileContent);

            if ($this->option("with-headers")) $headerRow = array_shift($csvLines); else $headerRow = "";

            foreach ($csvLines as $csvLine)
            {
                $headerNames = $this->getHeaderNames($headerRow);
    
                $parsedCsvLine = $this->parseCsvLine( $csvLine );
    
                if (count($parsedCsvLine) == count($headerNames))
                {
                    $data = array_combine($headerNames, $parsedCsvLine);
                    ProcessUserRecord::dispatch($data)->onQueue('users');
                }
                else
                {
                    $this->info('The csv file ' . $this->getCsvFileName() . ' is corrupt: '.print_r($parsedCsvLine, true));  
                    return 1; 
                }
            }
    
            $this->info('The csv file ' . $this->getCsvFileName() . ' with ' . count($csvLines) . ' users successfully queued');    
        }
        else
        {
            $this->info('The csv file ' . $this->getCsvFileName() . ' is empty');  
            return 1;  
        }
        return 0;
    }

    /**
     * Returns the file content to be processed
     *
     * @return string
     */
    private function getUserCsvFileContent()
    {
        return Storage::exists($this->getCsvFileName()) ? Storage::get($this->getCsvFileName()) : "";
    }

    /**
     * Returns the file name to be processed
     *
     * @return string
     */
    private function getCsvFileName()
    {
        return (config("apl.output_directory") ? config("apl.output_directory"). "/" : "").($this->argument('filename') ? $this->argument('filename') : config('apl.users_filename'));
    }

    /**
     * Parse one csv line into an array
     *
     * @return array
     */
    private function parseCsvLine(string $csvLine)
    {
        return str_getcsv( $csvLine );
    }

    /**
     * Creates an array with csv header field names
     *
     * @return array
     */
    private function getHeaderNames(string $csvHeaderRow)
    {
        $headerNames = array();

        if ($this->option("with-headers"))
        {
            $headerNames = str_getcsv($csvHeaderRow);
        }
        else
        {
            $headerNames = array(
                0 => "name",
                1 => "email",
                2 => "phone",
                3 => "password",
                4 => "deleted"
            );
        }
        return $headerNames;
    }
}
