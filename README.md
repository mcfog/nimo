Nimo Is your Middleware Organizer
=================================

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

+ [ ] PHPDoc
+ [ ] Tests
+ [ ] Tutorials / Examples
