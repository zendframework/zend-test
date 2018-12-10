<?php
/**
 * @see       https://github.com/zendframework/zend-test for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-test/blob/master/LICENSE.md New BSD License
 */
namespace ZendTest\Test\PHPUnit\Controller;

use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

class MemoryLeakTest extends AbstractControllerTestCase
{
    public static $memStart;

    public static function setUpBeforeClass()
    {
        self::$memStart = memory_get_usage(true);
    }

    public static function dataForMultipleTests()
    {
        return array_fill(0, 100, [null]);
    }

    /**
     * @dataProvider dataForMultipleTests
     * @param null $null
     */
    public function testMemoryConsumptionNotGrowing($null)
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.view.php'
        );
        $app = $this->getApplication();
        $app->run();

        $this->assertNull($null);

        // Test memory consumption is limited to 5 MB for 100 tests
        $this->assertLessThan(5242880, memory_get_usage(true) - self::$memStart);
    }
}
