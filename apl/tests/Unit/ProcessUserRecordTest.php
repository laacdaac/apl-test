<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ProcessUserRecordTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    
    }
    
    /**
     * Test if has ProcessUserRecord job.
     *
     * @return void
     */
    public function testHasProcessUserRecordJob()
    {
        $this->assertTrue(class_exists(\App\Jobs\ProcessUserRecord::class));
    }
    
    /**
     * Test if has User model.
     *
     * @return void
     */
    public function testHasUserModel()
    {
        $this->assertTrue(class_exists(\App\Models\User::class));
    }

}
