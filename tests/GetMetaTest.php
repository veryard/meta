<?php

namespace Veryard\Meta\Tests;

use Veryard\Meta\Exceptions\MetaException;
use Veryard\Meta\Meta;

class GetMetaTest extends TestCase
{
    /** @test */
    public function can_we_get_a_valid_response()
    {
        $meta = new Meta("https://producthunt.com/");

        $response = null;

        try {
            $response = $meta->get();
        } catch (MetaException $e) {
            $this->markTestFailed();
        }

        $this->markTestSucceeded();
    }

    /** @test */
    public function can_we_get_a_title()
    {
        $meta = new Meta("https://producthunt.com/");

        $response = null;

        try {
            $response = $meta->get();
        } catch (MetaException $e) {
            $this->markTestFailed();
        }

        $this->assertContains('title', $response);
    }
}
