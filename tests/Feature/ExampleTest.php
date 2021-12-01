<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // test that two strings matches
    public function test_that_strings_match(){
        $string1 = 'test';
        $string2 = 'test';

        $string3 = 'Testing';

        $this->assertSame($string1, $string2);

        $this->assertSame($string1, $string3);
    }
}
