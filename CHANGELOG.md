# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.2.0 - 2018-04-07

### Added

- [#60](https://github.com/zendframework/zend-test/pull/60) Added support for
  PHPUnit 7
- [#65](https://github.com/zendframework/zend-test/pull/65) Added support for
  query parameters in DELETE request in AbstractControllerTestCase

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#63](https://github.com/zendframework/zend-test/pull/63) Fixed compatibility
  with PHP 7.2

## 3.1.1 - 2017-10-29

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#55](https://github.com/zendframework/zend-test/pull/55) Fixes compatibility
  with PHPUnit 5.7.23 where empty expected exception message no longer means
  message is not checked.
- [#49](https://github.com/zendframework/zend-test/pull/49) Fixes missing alias
  for compatibility with PHPUnit <6.0

## 3.1.0 - 2017-05-01

### Added

- [#40](https://github.com/zendframework/zend-test/pull/40) and
  [#48](https://github.com/zendframework/zend-test/pull/48) add support for
  the PHPUnit 6 series, while retaining support for PHPUnit 4 and 5.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 3.0.2 - 2016-09-06

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#33](https://github.com/zendframework/zend-test/pull/33) fixes
  `queryContentRegexAssertion()` (used by `assertQueryContentRegex()` and
  `assertXpathQueryContentRegex()`) properly checks all matching nodes for
  content matching the regular expression, instead of only the first. The
  prevents false negative assertions from occuring.
- [#21](https://github.com/zendframework/zend-test/pull/21) updates the
  `sebastian/version` dependency to also allow v2.0 releases.
- [#31](https://github.com/zendframework/zend-test/pull/31) fixes an issue with
  the `AbstractControllerTestCase` when used to test a console request.
  Previously, routes with multiple literal flags were never matched; they now
  are.

## 3.0.1 - 2016-06-15

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#26](https://github.com/zendframework/zend-test/pull/26) fixes how
  `$traceErrors` works under PHP 7 and PHPUnit 5. Any zend-test-specific
  assertion failures now append a list of all exception messages to the base
  message when the flag is enabled.

## 3.0.0 - 2016-05-31

### Added

- This release adds support for zend-mvc v3.

### Deprecated

- Nothing.

### Removed

- This release removes support for PHP versions `< 5.6`.
- This release removes support for zend-mvc v2.

### Fixed

- Nothing.

## 2.6.2 - TBD

### Added

- [#22](https://github.com/zendframework/zend-test/pull/22) adds and publishes
  the documentation to https://zendframework.github.io/zend-test/

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.6.1 - 2016-03-02

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#20](https://github.com/zendframework/zend-test/pull/20) updates the zend-mvc
  requirement to 2.7.1, ensuring deprecation notices will not occur in the
  majority of circumstances.

## 2.6.0 - 2016-03-01

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#19](https://github.com/zendframework/zend-test/pull/19) updates the
  code to be forwards compatible with:
  - zend-eventmanager v3
  - zend-servicemanager v3
  - zend-stdlib v3
  - zend-mvc v2.7

## 2.5.3 - 2016-03-01

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#6](https://github.com/zendframework/zend-test/pull/6) updates the
  `AbstractControllerTestCase` to mark a test as failed if no route match occurs
  in a number of assertions that require a route match.
- [#7](https://github.com/zendframework/zend-test/pull/7) modifies the `reset()`
  method of the `AbstractControllerTestCase` to prevent rewriting the
  `$_SESSION` superglobal if it has not previously been enabled.

## 2.5.2 - 2015-12-09

### Added

- [#4](https://github.com/zendframework/zend-test/pull/4) PHPUnit v5 Support.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
