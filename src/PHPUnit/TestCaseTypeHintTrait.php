<?php

namespace Zend\Test\PHPUnit;

/**
 * @internal
 */
trait TestCaseTypeHintTrait
{
    protected function setUp() : void
    {
        if (method_exists($this, 'setUpCompat')) {
            $this->setUpCompat();
        }
    }

    protected function tearDown() : void
    {
        if (method_exists($this, 'tearDownCompat')) {
            $this->tearDownCompat();
        }
    }

    public static function setUpBeforeClass() : void
    {
        if (method_exists(static::class, 'setUpBeforeClassCompat')) {
            static::setUpBeforeClassCompat();
        }
    }

    public static function tearDownAfterClass() : void
    {
        if (method_exists(static::class, 'tearDownAfterClassCompat')) {
            static::tearDownAfterClassCompat();
        }
    }
}
