# Assertions

Assertions are at the heart of unit testing; you use them to verify that the
results are what you expect. To this end, `Zend\Test\PHPUnit\AbstractControllerTestCase`
provides a number of assertions to make testing your MVC apps and controllers
simpler.

## Request Assertions

It's often useful to assert against the last run action, controller, and module;
additionally, you may want to assert against the route that was matched. The
following assertions can help you in this regard:

- `assertModulesLoaded(array $modules)`: Assert that the given modules were
  loaded by the application.
- `assertModuleName($module)`: Assert that the given module was used in the last
  dispatched action.
- `assertControllerName($controller)`: Assert that the given controller
  identifier was selected in the last dispatched action.
- `assertControllerClass($controller)`: Assert that the given controller class
  was selected in the last dispatched action.
- `assertActionName($action)`: Assert that the given action was last dispatched.
- `assertMatchedRouteName($route)`: Assert that the given named route was
  matched by the router.

Each also has a 'Not' variant for negative assertions.

## CSS Selector Assertions

CSS selectors are an easy way to verify that certain artifacts are present in
the response content.  They also make it trivial to ensure that items necessary
for JavaScript UIs and/or AJAX integration will be present; most JS toolkits
provide some mechanism for manipulating DOM elements based on CSS selectors, so
the syntax would be the same.

This functionality is provided via [Zend\\Dom\\Query](https://zendframework.github.io/zend-dom/query/),
and integrated into a set of 'Query' assertions. Each of these assertions takes
as their first argument a CSS selector, with optionally additional arguments
and/or an error message, based on the assertion type. You can find the rules for
writing the CSS selectors in the zend-dom [Theory of Operation](https://zendframework.github.io/zend-dom/query/#theory-of-operation)
chapter. Query assertions include:

- `assertQuery($path)`: assert that one or more DOM elements matching the given
  CSS selector are present.
- `assertQueryContentContains($path, $match)`: assert that one or more DOM
  elements matching the given CSS selector are present, and that at least one
  contains the content provided in `$match`.
- `assertQueryContentRegex($path, $pattern)`: assert that one or more DOM
  elements matching the given CSS selector are present, and that at least one
  matches the regular expression provided in `$pattern`.
- `assertQueryCount($path, $count)`: assert that there are exactly `$count` DOM
  elements matching the given CSS selector present.
- `assertQueryCountMin($path, $count)`: assert that there are at least `$count`
  DOM elements matching the given CSS selector present.
- `assertQueryCountMax($path, $count)`: assert that there are no more than
  `$count` DOM elements matching the given CSS selector present.

All queries above also allow an optional `$message` argument; when provided,
that message will be used when displaying assertion failures.

Additionally, each of the above has a 'Not' variant that provides a negative
assertion: `assertNotQuery()`, `assertNotQueryContentContains()`,
`assertNotQueryContentRegex()`, and `assertNotQueryCount()`. (Note that the min
and max counts do not have these variants, for what should be obvious reasons.)

## XPath Assertions

Some developers are more familiar with XPath than with CSS selectors, and thus
XPath variants of all the Query assertions are also provided. These are:

- `assertXpathQuery($path)`: assert against the given XPath selection
- `assertNotXpathQuery($path)`: assert against the given XPath selection;
  negative assertions
- `assertXpathQueryCount($path, $count)`: assert against XPath selection; should
  contain exact number of nodes
- `assertNotXpathQueryCount($path, $count)`: assert against DOM/XPath selection;
  should not contain exact number of nodes
- `assertXpathQueryCountMin($path, $count)`: assert against XPath selection;
  should contain at least this number of nodes
- `assertXpathQueryCountMax($path, $count)`: assert against XPath selection;
  should contain no more than this number of nodes
- `assertXpathQueryContentContains($path, $match)`: assert against XPath
  selection; node should contain content
- `assertNotXpathQueryContentContains($path, $match)`: assert against XPath
 selection; node should not contain content
- `assertXpathQueryContentRegex($path, $pattern)`: assert against XPath
  selection; node should match content
- `assertNotXpathQueryContentRegex($path, $pattern)`: assert against XPath
  selection; node should not match content

## Redirect Assertions

Often an action will redirect. Instead of following the redirect,
`Zend\Test\PHPUnit\ControllerTestCase` allows you to test for redirects with a
handful of assertions.

- `assertRedirect()`: assert simply that a redirect has occurred.
- `assertRedirectTo($url)`: assert that a redirect has occurred, and that the
  value of the `Location` header is the `$url` provided.
- `assertRedirectRegex($pattern)`: assert that a redirect has occurred, and that
  the value of the `Location` header matches the regular expression provided by
  `$pattern`.

Each also has a 'Not' variant for negative assertions.

## Response Header Assertions

In addition to checking for redirect headers, you will often need to check for specific HTTP
response codes and headers; for instance, to determine whether an action results in a 404 or 500
response, or to ensure that JSON responses contain the appropriate `Content-Type` header. The
following assertions are available.

- `assertResponseStatusCode($code)`: assert that the response resulted in the
  given HTTP response code.
- `assertResponseHeader($header)`: assert that the response contains the given
  header.
- `assertResponseHeaderContains($header, $match)`: assert that the response
  contains the given header and that its content contains the given string.
- `assertResponseHeaderRegex($header, $pattern)`: assert that the response
  contains the given header and that its content matches the given regex.
- `assertHasResponseHeader($header)`: assert that the response header exists.

Additionally, each of the above assertions have a 'Not' variant for negative assertions.

- `assertResponseReasonPhrase($phrase)`: assert the the response has the given
  reason phrase

## Other Assertions

### Application Exceptions

- `assertApplicationException($type, $message = null)`: assert the given 
  application exception type and message.

### Template name

- `assertTemplateName($templateName)`: assert that a template was used somewhere
  in the view model tree.
- `assertNotTemplateName($templateName)`: assert that a template was not used
  somewhere in the view model tree.

