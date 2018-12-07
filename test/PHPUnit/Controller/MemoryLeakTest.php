<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendTest\Test\PHPUnit\Controller;

use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

class MemoryLeakTest extends AbstractControllerTestCase
{
    const MAX_MEMORY_USAGE_PHP7 = 24 * 1024 * 1024; // 24 MB
    const MAX_MEMORY_USAGE_PHP5 = 52 * 1024 * 1024; // 52 MB

    public static $memory_usage;

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

    public static function dataProvider()
    {
        return array_fill(0, 1000, [ null ]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testMemoryConsumptionLessThan($variable)
    {
        $this->getApplication();

        $this->assertNull($variable);
        if (version_compare(phpversion(), '7.0.0', '<')) {
            $this->assertLessThan(self::MAX_MEMORY_USAGE_PHP5, memory_get_usage(true));
        } else {
            $this->assertLessThan(self::MAX_MEMORY_USAGE_PHP7, memory_get_usage(true));
        }
    }
}
