<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendTest\Test\PHPUnit\Util;

use PHPUnit\Framework\TestCase;
use Zend\ModuleManager\Exception\RuntimeException;
use Zend\Test\Util\ModuleLoader;

class ModuleLoaderTest extends TestCase
{
    public function tearDownCacheDir()
    {
        $cacheDir = sys_get_temp_dir() . '/zf2-module-test';
        if (is_dir($cacheDir)) {
            static::rmdir($cacheDir);
        }
    }

    public static function rmdir($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? static::rmdir("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    public function setUp()
    {
        $this->tearDownCacheDir();
    }

    public function tearDown()
    {
        $this->tearDownCacheDir();
    }

    public function testCanLoadModule()
    {
        require_once __DIR__ . '/../../_files/Baz/Module.php';

        $loader = new ModuleLoader(['Baz']);
        $baz = $loader->getModule('Baz');
        $this->assertInstanceOf('Baz\Module', $baz);
    }

    public function testCanNotLoadModule()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('could not be initialized');

        $loader = new ModuleLoader(['FooBaz']);
    }

    public function testCanLoadModuleWithPath()
    {
        $loader = new ModuleLoader(['Baz' => __DIR__ . '/../../_files/Baz']);
        $baz = $loader->getModule('Baz');
        $this->assertInstanceOf('Baz\Module', $baz);
    }

    public function testCanLoadModules()
    {
        require_once __DIR__ . '/../../_files/Baz/Module.php';
        require_once __DIR__ . '/../../_files/modules-path/with-subdir/Foo/Module.php';

        $loader = new ModuleLoader(['Baz', 'Foo']);
        $baz = $loader->getModule('Baz');
        $this->assertInstanceOf('Baz\Module', $baz);
        $foo = $loader->getModule('Foo');
        $this->assertInstanceOf('Foo\Module', $foo);
    }

    public function testCanLoadModulesWithPath()
    {
        $loader = new ModuleLoader([
            'Baz' => __DIR__ . '/../../_files/Baz',
            'Foo' => __DIR__ . '/../../_files/modules-path/with-subdir/Foo',
        ]);

        $fooObject = $loader->getServiceManager()->get('FooObject');
        $this->assertInstanceOf('stdClass', $fooObject);
    }

    public function testCanLoadModulesFromConfig()
    {
        $config = include __DIR__ . '/../../_files/application.config.php';
        $loader = new ModuleLoader($config);
        $baz = $loader->getModule('Baz');
        $this->assertInstanceOf('Baz\Module', $baz);
    }

    public function testCanGetService()
    {
        $loader = new ModuleLoader(['Baz' => __DIR__ . '/../../_files/Baz']);

        $this->assertInstanceOf(
            'Zend\ServiceManager\ServiceLocatorInterface',
            $loader->getServiceManager()
        );
        $this->assertInstanceOf(
            'Zend\ModuleManager\ModuleManager',
            $loader->getModuleManager()
        );
        $this->assertInstanceOf(
            'Zend\Mvc\ApplicationInterface',
            $loader->getApplication()
        );
    }
}
