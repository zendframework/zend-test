<?php
/**
 * @see       https://github.com/zendframework/zend-test for the canonical source repository
 * @copyright Copyright (c) 2005-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-test/blob/master/LICENSE.md New BSD License
 */
namespace ZendTest\Test\PHPUnit\Controller;

use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

class MemoryLeakTest extends AbstractControllerTestCase
{
    public static $mem_start;

    public static function setUpBeforeClass()
    {
        self::$mem_start = memory_get_usage(true);
    }

    public function setUp()
    {
        parent::setUp();

        $this->setApplicationConfig(
            [
                'modules'                 => [
                    'Zend\\Router',
                ],
                'module_listener_options' => [],
            ]
        );
    }

    public static function dataForMultipleTests()
    {
        return array_fill(0, 100, [null]);
    }

    /**
     * @dataProvider dataForMultipleTests
     */
    public function testMemoryConsumptionNotGrowing($null)
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.view.php'
        );
        $app = $this->getApplication();
        $app->run();

        $this->assertNull($null);

        if (version_compare(phpversion(), '7.0.0', '<')) {
            // Test memory consumption is limited to 5 MB for 100 tests on PHP 5.6
            $this->assertLessThan(5242880, memory_get_usage(true) - self::$mem_start);
        } else {
            $this->assertEquals(0, memory_get_usage(true) - self::$mem_start);
        }
    }
}
