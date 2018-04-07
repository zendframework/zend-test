<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendTest\Test\PHPUnit\Controller;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException;
use Zend\Console\Console;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use ZendTest\Test\ExpectedExceptionTrait;

/**
 * @group      Zend_Test
 */
class AbstractControllerTestCaseTest extends AbstractHttpControllerTestCase
{
    use ExpectedExceptionTrait;

    protected $traceError = true;
    protected $traceErrorCache = true;

    public function tearDownCacheDir()
    {
        vfsStreamWrapper::register();
        $cacheDir = vfsStream::url('zf2-module-test');
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

    protected function setUp()
    {
        $this->traceErrorCache = $this->traceError;
        $this->tearDownCacheDir();
        Console::overrideIsConsole(null);
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        parent::setUp();
    }

    protected function tearDown()
    {
        $this->traceError = $this->traceErrorCache;
        $this->tearDownCacheDir();
        parent::tearDown();
    }

    public function testModuleCacheIsDisabled()
    {
        $config = $this->getApplicationConfig();
        $config = $config['module_listener_options']['cache_dir'];
        $this->assertEquals(0, count(glob($config . '/*.php')));
    }

    public function testCanNotDefineApplicationConfigWhenApplicationIsBuilt()
    {
        // cosntruct app
        $this->getApplication();

        $this->expectedException('Zend\Stdlib\Exception\LogicException');
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
    }

    public function testUseOfRouter()
    {
        // default value
        $this->assertEquals(false, $this->useConsoleRequest);
    }

    public function testApplicationClass()
    {
        $applicationClass = get_class($this->getApplication());
        $this->assertEquals($applicationClass, 'Zend\Mvc\Application');
    }

    public function testApplicationClassAndTestRestoredConsoleFlag()
    {
        $this->assertTrue(Console::isConsole(), '1. Console::isConsole returned false in initial test');
        $this->getApplication();
        $this->assertFalse(Console::isConsole(), '2. Console::isConsole returned true after retrieving application');
        $this->tearDown();
        $this->assertTrue(Console::isConsole(), '3. Console::isConsole returned false after tearDown');

        Console::overrideIsConsole(false);
        parent::setUp();

        $this->assertFalse(Console::isConsole(), '4. Console::isConsole returned true after parent::setUp');
        $this->getApplication();
        $this->assertFalse(Console::isConsole(), '5. Console::isConsole returned true after retrieving application');

        parent::tearDown();

        $this->assertFalse(Console::isConsole(), '6. Console.isConsole returned true after parent::tearDown');
    }

    public function testApplicationServiceLocatorClass()
    {
        $smClass = get_class($this->getApplicationServiceLocator());
        $this->assertEquals($smClass, 'Zend\ServiceManager\ServiceManager');
    }

    public function testAssertApplicationRequest()
    {
        $this->assertEquals(true, $this->getRequest() instanceof RequestInterface);
    }

    public function testAssertApplicationResponse()
    {
        $this->assertEquals(true, $this->getResponse() instanceof ResponseInterface);
    }

    public function testAssertModuleName()
    {
        $this->dispatch('/tests');

        // tests with case insensitive
        $this->assertModuleName('baz');
        $this->assertModuleName('Baz');
        $this->assertModuleName('BAz');

        $this->expectedException(
            ExpectationFailedException::class,
            'actual module name is "baz"' // check actual module is display
        );
        $this->assertModuleName('Application');
    }

    public function testAssertExceptionDetailsPresentWhenTraceErrorIsEnabled()
    {
        $this->traceError = true;
        $this->dispatch('/tests');
        $this->getApplication()->getMvcEvent()->setParam(
            'exception',
            new RuntimeException('Expected exception message')
        );

        $caught = false;
        try {
            $this->assertModuleName('Application');
        } catch (ExpectationFailedException $ex) {
            $caught = true;
            $message = $ex->getMessage();
        }

        $this->assertTrue($caught, 'Did not catch expected exception!');

        $this->assertContains('actual module name is "baz"', $message);
        $this->assertContains("Exception 'RuntimeException' with message 'Expected exception message'", $message);
        $this->assertContains(__FILE__, $message);
    }

    public function testAssertExceptionDetailsNotPresentWhenTraceErrorIsDisabled()
    {
        $this->traceError = false;
        $this->dispatch('/tests');
        $this->getApplication()->getMvcEvent()->setParam(
            'exception',
            new RuntimeException('Expected exception message')
        );

        $caught = false;
        try {
            $this->assertModuleName('Application');
        } catch (ExpectationFailedException $ex) {
            $caught = true;
            $message = $ex->getMessage();
        }

        $this->assertTrue($caught, 'Did not catch expected exception!');

        $this->assertContains('actual module name is "baz"', $message);
        $this->assertNotContains("Exception 'RuntimeException' with message 'Expected exception message'", $message);
        $this->assertNotContains(__FILE__, $message);
    }

    public function testAssertNotModuleName()
    {
        $this->dispatch('/tests');
        $this->assertNotModuleName('Application');

        $this->expectedException(ExpectationFailedException::class);
        $this->assertNotModuleName('baz');
    }

    public function testAssertControllerClass()
    {
        $this->dispatch('/tests');

        // tests with case insensitive
        $this->assertControllerClass('IndexController');
        $this->assertControllerClass('Indexcontroller');
        $this->assertControllerClass('indexcontroller');

        $this->expectedException(
            ExpectationFailedException::class,
            'actual controller class is "indexcontroller"' // check actual controller class is display
        );
        $this->assertControllerClass('Index');
    }

    public function testAssertNotControllerClass()
    {
        $this->dispatch('/tests');
        $this->assertNotControllerClass('Index');

        $this->expectedException(ExpectationFailedException::class);
        $this->assertNotControllerClass('IndexController');
    }

    public function testAssertControllerName()
    {
        $this->dispatch('/tests');

        // tests with case insensitive
        $this->assertControllerName('baz_index');
        $this->assertControllerName('Baz_index');
        $this->assertControllerName('BAz_index');

        $this->expectedException(
            ExpectationFailedException::class,
            'actual controller name is "baz_index"' // check actual controller name is display
        );
        $this->assertControllerName('baz');
    }

    public function testAssertNotControllerName()
    {
        $this->dispatch('/tests');
        $this->assertNotControllerName('baz');

        $this->expectedException(ExpectationFailedException::class);
        $this->assertNotControllerName('baz_index');
    }

    public function testAssertActionName()
    {
        $this->dispatch('/tests');

        // tests with case insensitive
        $this->assertActionName('unittests');
        $this->assertActionName('unitTests');
        $this->assertActionName('UnitTests');

        $this->expectedException(
            ExpectationFailedException::class,
            'actual action name is "unittests"' // check actual action name is display
        );
        $this->assertActionName('unit');
    }

    public function testAssertNotActionName()
    {
        $this->dispatch('/tests');
        $this->assertNotActionName('unit');

        $this->expectedException(ExpectationFailedException::class);
        $this->assertNotActionName('unittests');
    }

    public function testAssertMatchedRouteName()
    {
        $this->dispatch('/tests');

        // tests with case insensitive
        $this->assertMatchedRouteName('myroute');
        $this->assertMatchedRouteName('myRoute');
        $this->assertMatchedRouteName('MyRoute');

        $this->expectedException(
            ExpectationFailedException::class,
            'actual matched route name is "myroute"' // check actual matched route name is display
        );
        $this->assertMatchedRouteName('route');
    }

    public function testAssertNotMatchedRouteName()
    {
        $this->dispatch('/tests');
        $this->assertNotMatchedRouteName('route');

        $this->expectedException(ExpectationFailedException::class);
        $this->assertNotMatchedRouteName('myroute');
    }

    public function testAssertNoMatchedRoute()
    {
        $this->dispatch('/invalid');
        $this->assertNoMatchedRoute();
    }

    public function testAssertNoMatchedRouteWithMatchedRoute()
    {
        $this->dispatch('/tests');
        $this->expectedException(ExpectationFailedException::class, 'no route matched');
        $this->assertNoMatchedRoute();
    }

    public function testControllerNameWithNoRouteMatch()
    {
        $this->dispatch('/invalid');
        $this->expectedException(ExpectationFailedException::class, 'No route matched');
        $this->assertControllerName('something');
    }

    public function testNotControllerNameWithNoRouteMatch()
    {
        $this->dispatch('/invalid');
        $this->expectedException(ExpectationFailedException::class, 'No route matched');
        $this->assertNotControllerName('something');
    }

    public function testActionNameWithNoRouteMatch()
    {
        $this->dispatch('/invalid');
        $this->expectedException(ExpectationFailedException::class, 'No route matched');
        $this->assertActionName('something');
    }

    public function testNotActionNameWithNoRouteMatch()
    {
        $this->dispatch('/invalid');
        $this->expectedException(ExpectationFailedException::class, 'No route matched');
        $this->assertNotActionName('something');
    }

    public function testMatchedRouteNameWithNoRouteMatch()
    {
        $this->dispatch('/invalid');
        $this->expectedException(ExpectationFailedException::class, 'No route matched');
        $this->assertMatchedRouteName('something');
    }

    public function testNotMatchedRouteNameWithNoRouteMatch()
    {
        $this->dispatch('/invalid');
        $this->expectedException(ExpectationFailedException::class, 'No route matched');
        $this->assertNotMatchedRouteName('something');
    }

    public function testControllerClassWithNoRoutematch()
    {
        $this->dispatch('/invalid');
        $this->expectedException(ExpectationFailedException::class, 'No route matched');
        $this->assertControllerClass('something');
    }

    /**
     * Sample tests on Application errors events
     */
    public function testAssertApplicationErrorsEvents()
    {
        $this->url('/bad-url');
        $result = $this->triggerApplicationEvent(MvcEvent::EVENT_ROUTE);
        $this->assertEquals(true, $result->stopped());
        $this->assertEquals(Application::ERROR_ROUTER_NO_MATCH, $this->getApplication()->getMvcEvent()->getError());
    }

    public function testDispatchRequestUri()
    {
        $this->dispatch('/tests');
        $this->assertEquals('/tests', $this->getApplication()->getRequest()->getRequestUri());
    }

    public function testDefaultDispatchMethod()
    {
        $this->dispatch('/tests');
        $this->assertEquals('GET', $this->getRequest()->getMethod());
    }

    public function testDispatchMethodSetOnRequest()
    {
        $this->getRequest()->setMethod('POST');
        $this->dispatch('/tests');
        $this->assertEquals('POST', $this->getRequest()->getMethod());
    }

    public function testExplicitDispatchMethodOverrideRequestMethod()
    {
        $this->getRequest()->setMethod('POST');
        $this->dispatch('/tests', 'GET');
        $this->assertEquals('GET', $this->getRequest()->getMethod());
    }

    public function testPutRequestParams()
    {
        $this->dispatch('/tests', 'PUT', ['a' => 1]);
        $this->assertEquals('a=1', $this->getRequest()->getContent());
    }

    public function testPreserveContentOfPutRequest()
    {
        $this->getRequest()->setMethod('PUT');
        $this->getRequest()->setContent('my content');
        $this->dispatch('/tests');
        $this->assertEquals('my content', $this->getRequest()->getContent());
    }

    /**
     * @group 6399
     */
    public function testPatchRequestParams()
    {
        $this->dispatch('/tests', 'PATCH', ['a' => 1]);
        $this->assertEquals('a=1', $this->getRequest()->getContent());
    }

    /**
     * @group 6399
     */
    public function testPreserveContentOfPatchRequest()
    {
        $this->getRequest()->setMethod('PATCH');
        $this->getRequest()->setContent('my content');
        $this->dispatch('/tests');
        $this->assertEquals('my content', $this->getRequest()->getContent());
    }

    public function testExplicityPutParamsOverrideRequestContent()
    {
        $this->getRequest()->setContent('my content');
        $this->dispatch('/tests', 'PUT', ['a' => 1]);
        $this->assertEquals('a=1', $this->getRequest()->getContent());
    }

    /**
     * @group 6636
     * @group 6637
     */
    public function testCanHandleMultidimensionalParams()
    {
        $this->dispatch('/tests', 'PUT', ['a' => ['b' => 1]]);
        $this->assertEquals('a[b]=1', urldecode($this->getRequest()->getContent()));
    }

    public function testAssertTemplateName()
    {
        $this->dispatch('/tests');

        $this->assertTemplateName('layout/layout');
        $this->assertTemplateName('baz/index/unittests');
    }

    public function testAssertNotTemplateName()
    {
        $this->dispatch('/tests');

        $this->assertNotTemplateName('template/does/not/exist');
    }

    public function testCustomResponseObject()
    {
        $this->dispatch('/custom-response');
        $this->assertResponseStatusCode(999);
    }

    public function testResetDoesNotCreateSessionIfNoSessionExists()
    {
        if (! extension_loaded('session')) {
            $this->markTestSkipped('No session extension loaded');
        }

        $this->reset();

        $this->assertFalse(array_key_exists('_SESSION', $GLOBALS));
    }

    public function method()
    {
        yield 'null' => [null];
        yield 'get' => ['GET'];
        yield 'delete' => ['DELETE'];
        yield 'post' => ['POST'];
        yield 'put' => ['PUT'];
        yield 'patch' => ['PATCH'];
    }

    /**
     * @dataProvider method
     *
     * @param null|string $method
     */
    public function testDispatchWithNullParams($method)
    {
        $this->dispatch('/custom-response', $method, null);
        $this->assertResponseStatusCode(999);
    }

    public function testQueryParamsDelete()
    {
        $this->dispatch('/tests', 'DELETE', ['foo' => 'bar']);
        $this->assertEquals('foo=bar', $this->getRequest()->getQuery()->toString());
    }
}
