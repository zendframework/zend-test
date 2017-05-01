<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendTest\Test\PHPUnit\Controller;

use PHPUnit\Framework\ExpectationFailedException;
use Zend\Router\RouteMatch;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use ZendTest\Test\ExpectedExceptionTrait;

/**
 * @group      Zend_Test
 */
class AbstractConsoleControllerTestCaseTest extends AbstractConsoleControllerTestCase
{
    use ExpectedExceptionTrait;

    protected function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        parent::setUp();
    }

    public function testUseOfRouter()
    {
        $this->assertEquals(true, $this->useConsoleRequest);
    }

    public function testAssertResponseStatusCode()
    {
        $this->dispatch('--console');
        $this->assertResponseStatusCode(0);

        $this->expectedException(
            ExpectationFailedException::class,
            'actual status code is "0"' // check actual status code is display
        );
        $this->assertResponseStatusCode(1);
    }

    public function testAssertNotResponseStatusCode()
    {
        $this->dispatch('--console');
        $this->assertNotResponseStatusCode(1);

        $this->expectedException(ExpectationFailedException::class);
        $this->assertNotResponseStatusCode(0);
    }

    public function testAssertResponseStatusCodeWithBadCode()
    {
        $this->dispatch('--console');
        $this->expectedException(
            ExpectationFailedException::class,
            'Console status code assert value must be O (valid) or 1 (error)'
        );
        $this->assertResponseStatusCode(2);
    }

    public function testAssertNotResponseStatusCodeWithBadCode()
    {
        $this->dispatch('--console');
        $this->expectedException(
            ExpectationFailedException::class,
            'Console status code assert value must be O (valid) or 1 (error)'
        );
        $this->assertNotResponseStatusCode(2);
    }

    public function testAssertConsoleOutputContains()
    {
        $this->dispatch('--console');
        $this->assertConsoleOutputContains('foo');
        $this->assertConsoleOutputContains('foo, bar');

        $this->expectedException(
            ExpectationFailedException::class,
            'actual content is "foo, bar"' // check actual content is display
        );
        $this->assertConsoleOutputContains('baz');
    }

    public function testNotAssertConsoleOutputContains()
    {
        $this->dispatch('--console');
        $this->assertNotConsoleOutputContains('baz');

        $this->expectedException(ExpectationFailedException::class);
        $this->assertNotConsoleOutputContains('foo');
    }

    public function testAssertMatchedArgumentsWithValue()
    {
        $this->dispatch('filter --date="2013-03-07 00:00:00" --id=10 --text="custom text"');
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        $this->assertInstanceOf(RouteMatch::class, $routeMatch, 'Did not receive a route match?');
        $this->assertEquals("2013-03-07 00:00:00", $routeMatch->getParam('date'));
        $this->assertEquals("10", $routeMatch->getParam('id'));
        $this->assertEquals("custom text", $routeMatch->getParam('text'));
    }

    /**
     * @group 6837
     */
    public function testAssertMatchedArgumentsWithMandatoryValue()
    {
        $this->dispatch("foo --bar='FOO' --baz='ARE'");
        /** @var \Zend\Mvc\Router\Console\RouteMatch $routeMatch */
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        $this->assertNotNull($routeMatch);
        $this->assertEquals('arguments-mandatory', $routeMatch->getMatchedRouteName());

        $this->reset();

        $this->dispatch('foo --bar="FOO" --baz="ARE"');
        /** @var \Zend\Mvc\Router\Console\RouteMatch $routeMatch */
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        $this->assertNotNull($routeMatch);
        $this->assertEquals('arguments-mandatory', $routeMatch->getMatchedRouteName());
    }

    public function testAssertMatchedArgumentsWithValueWithoutEqualsSign()
    {
        $this->dispatch('filter --date "2013-03-07 00:00:00" --id=10 --text="custom text"');
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        $this->assertInstanceOf(RouteMatch::class, $routeMatch, 'Did not receive a route match?');
        $this->assertEquals("2013-03-07 00:00:00", $routeMatch->getParam('date'));
        $this->assertEquals("10", $routeMatch->getParam('id'));
        $this->assertEquals("custom text", $routeMatch->getParam('text'));
    }

    public function testAssertMatchedArgumentsWithLiteralFlags()
    {
        $this->dispatch('literal --foo --bar');
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        $this->assertInstanceOf(RouteMatch::class, $routeMatch, 'Did not receive a route match?');
        $this->assertMatchedRouteName('arguments-literal');
        $this->assertTrue($routeMatch->getParam('foo'));
        $this->assertTrue($routeMatch->getParam('bar'));
        $this->assertFalse($routeMatch->getParam('optional'));
        $this->assertNull($routeMatch->getParam('doo'));

        $this->reset();

        $this->dispatch('literal --foo --bar --doo test');
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        $this->assertInstanceOf(RouteMatch::class, $routeMatch, 'Did not receive a route match?');
        $this->assertMatchedRouteName('arguments-literal');
        $this->assertTrue($routeMatch->getParam('foo'));
        $this->assertTrue($routeMatch->getParam('bar'));
        $this->assertFalse($routeMatch->getParam('optional'));
        $this->assertSame('test', $routeMatch->getParam('doo'));
    }
}
