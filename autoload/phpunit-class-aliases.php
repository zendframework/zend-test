<?php
/**
 * @see       https://github.com/zendframework/zend-test for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-test/blob/master/LICENSE.md New BSD License
 */

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

if (! class_exists(ExpectationFailedException::class)) {
    class_alias(\PHPUnit_Framework_ExpectationFailedException::class, ExpectationFailedException::class);
}

if (! class_exists(TestCase::class)) {
    class_alias(\PHPUnit_Framework_TestCase::class, TestCase::class);
}
