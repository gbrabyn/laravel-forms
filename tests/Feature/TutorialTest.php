<?php

namespace Tests\Feature;

use Tests\TestCase;

class TutorialTest extends TestCase
{
    public function testHomeShouldLoad()
    {
        $response = $this->get(route('tutorial.intro'));

        $response->assertStatus(200);
        // $response->assertViewIs('tutorial.introduction');
        // $response->assertSee('Get a base understanding of how to build complex');
    }

    public function testInstructionsShouldLoad()
    {
        $response = $this->get(route('tutorial.instructions'));

        $response->assertStatus(200);
        // $response->assertSee('The source code for this website can be found at');
    }

    public function testTechniquesShouldLoad()
    {
        $response = $this->get(route('tutorial.techniques'));

        $response->assertStatus(200);
        // $response->assertSee('Techniques for building Dynamic Forms');
    }

    public function testContentSecurityPolicy()
    {
        $response = $this->get(route('tutorial.techniques'));

        $response->assertHeader('Content-Security-Policy', "frame-ancestors 'self'");
    }
}
