# Unit testing with PHPUnit

`Zend\Test\PHPUnit` provides an abstract `TestCase` for zend-mvc applications
that contains assertions for testing against a variety of responsibilities.
Probably the easiest way to understand what it can do is to see an example.

The following is a simple test case for an `IndexController` to verify things
such as the final HTTP status code, and the discovered controller and action
names:

```php
namespace ApplicationTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/path/to/application/config/test/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('application');
        $this->assertControllerName('application_index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }
}
```

The setup of the test case should define the application config. You can use
several configuration files to test module dependencies or your current
application config.

## Setup your TestCase

As noted in the previous example, all MVC test cases should extend
`AbstractHttpControllerTestCase`.  This class in turn extends
`PHPUnit_Framework_TestCase`, and gives you all the structure and assertions
you'd expect from PHPUnit, as well as some scaffolding and assertions specific
to zend-mvc.

In order to test your MVC application, you will need to setup the application
configuration. Use the `setApplicationConfig()` method to do this:

```php
public function setUp()
{
    $this->setApplicationConfig(
        include '/path/to/application/config/test/application.config.php'
    );
    parent::setUp();
}
```

Once the application is set up, you can write your tests. To help debug tests,
you can activate the flag `traceError` to throw MVC exceptions during test
execution:

```php
namespace ApplicationTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
}
```

## Testing your Controllers and MVC Applications

Once you have your application config in place, you can begin testing. Testing
is basically as you would expect in an PHPUnit test suite, with a few minor
differences.

First, you will need to dispatch a URL to test, using the `dispatch` method of
the TestCase:

```php
public function testIndexAction()
{
    $this->dispatch('/');
}
```

There will be times, however, that you need to provide extra information: query
string arguments, POST, variables, cookies, etc.  You can populate the request
with that information:

```php
public function testIndexAction()
{
    $this->getRequest()
        ->setMethod('POST')
        ->setPost(new Parameters(['argument' => 'value']));
    $this->dispatch('/');
}
```

You can populate query string arguments or POST variables directly with the
`dispatch` method:

```php
public function testIndexAction()
{
    $this->dispatch('/', 'POST', ['argument' => 'value']);
}
```

Query string arguments can be provided in the URL you dispatch:

```php
public function testIndexAction()
{
    $this->dispatch('/tests?foo=bar&baz=foo');
}
```

Now that the request is made, it's time to start [making assertions](assertions.md) against it.
