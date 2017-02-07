<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Test\PHPUnit;

use PHPUnit\Framework\ExpectationFailedException;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

if (! class_exists(ExpectationFailedException::class)) {
    class_alias(\PHPUnit_Framework_ExpectationFailedException::class, ExpectationFailedException::class);
}

/**
 * @group      Zend_Test
 */
class ModuleDependenciesTest extends AbstractHttpControllerTestCase
{
    public function testDependenciesModules()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.with.dependencies.php'
        );
        $sm = $this->getApplicationServiceLocator();
        $this->assertEquals(true, $sm->has('FooObject'));
        $this->assertEquals(true, $sm->has('BarObject'));

        $this->assertModulesLoaded(['Foo', 'Bar']);
        $this->expectException(ExpectationFailedException::class);
        $this->assertModulesLoaded(['Foo', 'Bar', 'Unknow']);
    }

    public function testBadDependenciesModules()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.with.dependencies.disabled.php'
        );
        $sm = $this->getApplicationServiceLocator();
        $this->assertEquals(false, $sm->has('FooObject'));
        $this->assertEquals(true, $sm->has('BarObject'));

        $this->assertNotModulesLoaded(['Foo']);
        $this->expectException(ExpectationFailedException::class);
        $this->assertNotModulesLoaded(['Foo', 'Bar']);
    }
}
