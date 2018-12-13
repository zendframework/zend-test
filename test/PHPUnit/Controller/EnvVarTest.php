<?php
/**
 * @see       https://github.com/zendframework/zend-test for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-test/blob/master/LICENSE.md New BSD License
 */
namespace ZendTest\Test\PHPUnit\Controller;

use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

class EnvVarTest extends AbstractControllerTestCase
{
    public static function setUpBeforeClass()
    {
        $_SERVER['test'] = 'preserved';
    }

    public static function tearDownAfterClass()
    {
        unset($_SERVER['test']);
    }

    public function setUp()
    {
        // setUp not call then the global var test should not be erase
    }

    public function testGlobalVarAlwaysExist()
    {
        $this->assertArrayHasKey('test', $_SERVER);
        $this->assertSame($_SERVER['test'], 'preserved');
    }

    public function testGlobalVarAlwaysExistAgain()
    {
        $this->assertArrayHasKey('test', $_SERVER);
        $this->assertSame($_SERVER['test'], 'preserved');
    }
}
