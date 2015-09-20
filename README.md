Nimo Is your Middleware Organizer
=================================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mcfog/nimo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mcfog/nimo/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/mcfog/nimo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mcfog/nimo/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/mcfog/nimo/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mcfog/nimo/build-status/master)

inspired by [relayphp](https://github.com/relayphp/Relay.Relay) and [zend-stratigility](https://github.com/zendframework/zend-stratigility) 

### Features

+ error handling
  + trigger error via exception or `$next($req, $res, $error)`
  + handle that with `IErrorMiddleware`
  + skip normal middleware when there is an error, vice versa
+ bundled middlewares
  + `CallbackErrorMiddleware`: wrap error handling callback
  + `ConditionMiddleware`: conditionally skip inner middleware
  + `SwitchMiddleware`: choose a middleware to run (routing)

### todos

+ [x] PHPDoc
+ [x] Tests
+ [ ] Tutorials / Examples
