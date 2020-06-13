<?php

declare(strict_types=1);

namespace Veryard\Meta\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function markTestSucceeded(): void
    {
        $this->assertTrue(true);
    }

    protected function markTestFailed(): void
    {
        $this->assertFalse(false);
    }
}
