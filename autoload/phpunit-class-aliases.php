<?php
/**
 * @see       https://github.com/zendframework/zend-test for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-test/blob/master/LICENSE.md New BSD License
 */

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Version;

if (! class_exists(ExpectationFailedException::class)) {
    class_alias(\PHPUnit_Framework_ExpectationFailedException::class, ExpectationFailedException::class);
}

if (! class_exists(TestCase::class)) {
    class_alias(\PHPUnit_Framework_TestCase::class, TestCase::class);
}

// Compatibility with PHPUnit 8.0
// We need to use "magic" trait \Zend\Test\PHPUnit\TestCaseTrait
// and instead of setUp/tearDown method in test case
// we should have setUpCompat/tearDownCompat.
if (class_exists(Version::class)
    && version_compare(Version::id(), '8.0.0') >= 0
) {
    class_alias(\Zend\Test\PHPUnit\TestCaseTypeHintTrait::class, \Zend\Test\PHPUnit\TestCaseTrait::class);
} else {
    class_alias(\Zend\Test\PHPUnit\TestCaseNoTypeHintTrait::class, \Zend\Test\PHPUnit\TestCaseTrait::class);
}
