<?php

namespace Zend\Test\PHPUnit;

/**
 * @internal
 */
trait TestCaseNoTypeHintTrait
{
    protected function setUp()
    {
        if (method_exists($this, 'setUpCompat')) {
            $this->setUpCompat();
        }
    }

    protected function tearDown()
    {
        if (method_exists($this, 'tearDownCompat')) {
            $this->tearDownCompat();
        }
    }

    public static function setUpBeforeClass()
    {
        if (method_exists(__CLASS__, 'setUpBeforeClassCompat')) {
            static::setUpBeforeClassCompat();
        }
    }

    public static function tearDownAfterClass()
    {
        if (method_exists(__CLASS__, 'tearDownAfterClassCompat')) {
            static::tearDownAfterClassCompat();
        }
    }
}
