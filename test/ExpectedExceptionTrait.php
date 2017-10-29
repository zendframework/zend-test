<?php
/**
 * @see       https://github.com/zendframework/zend-test for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-test/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Test;

trait ExpectedExceptionTrait
{
    /**
     * @param string $exceptionClass Expected exception class
     * @param string $message String expected within exception message, if any
     * @return void
     */
    public function expectedException($exceptionClass, $message = '')
    {
        if (! method_exists($this, 'expectException')) {
            // For old PHPUnit 4
            $this->setExpectedException($exceptionClass, $message);
            return;
        }

        $this->expectException($exceptionClass);

        if (! empty($message)) {
            $this->expectExceptionMessage($message);
        }
    }
}
