<?php

namespace Tests\Unit;

use Tests\TestCase;

class ProcessUserCsvTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    
    }
    
    /**
     * Test if has ProcessUserCsv command.
     *
     * @return void
     */
    public function testHasProcessUserCsvCommand()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\ProcessUserCsv::class));
    }

    /**
     * Test if apl config exists
     *
     * @return void
     */
    public function testHasConfig()
    {
        $this->assertTrue(file_exists("config/apl.php"));
    }

    /**
     * Test if config value users_filename is not empty
     *
     * @return void
     */
    public function testHasConfigUsersFilename()
    {
        $this->assertNotEmpty(config("apl.users_filename"));
    }

}
