[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7.1-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/phpgears/cqrs-symfony-messenger.svg?style=flat-square)](https://packagist.org/packages/phpgears/cqrs-symfony-messenger)
[![License](https://img.shields.io/github/license/phpgears/cqrs-symfony-messenger.svg?style=flat-square)](https://github.com/phpgears/cqrs-symfony-messenger/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/com/phpgears/cqrs-symfony-messenger.svg?style=flat-square)](https://travis-ci.com/github/phpgears/cqrs-symfony-messenger)
[![Style Check](https://styleci.io/repos/168892182/shield)](https://styleci.io/repos/168892182)
[![Code Quality](https://img.shields.io/scrutinizer/g/phpgears/cqrs-symfony-messenger.svg?style=flat-square)](https://scrutinizer-ci.com/g/phpgears/cqrs-symfony-messenger)
[![Code Coverage](https://img.shields.io/coveralls/phpgears/cqrs-symfony-messenger.svg?style=flat-square)](https://coveralls.io/github/phpgears/cqrs-symfony-messenger)

[![Total Downloads](https://img.shields.io/packagist/dt/phpgears/cqrs-symfony-messenger.svg?style=flat-square)](https://packagist.org/packages/phpgears/cqrs-symfony-messenger/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/phpgears/cqrs-symfony-messenger.svg?style=flat-square)](https://packagist.org/packages/phpgears/cqrs-symfony-messenger/stats)

# CQRS with Symfony's Messenger

CQRS implementation with Symfony's Messenger

## Installation

### Composer

```
composer require phpgears/cqrs-symfony-messenger
```

## Usage

Require composer autoload file

```php
require './vendor/autoload.php';
```

### Commands Bus

Just as simple as adding HandleMessageMiddleware to a Messenger's middleware list

```php
use Gears\CQRS\Symfony\Messenger\CommandBus;
use Gears\CQRS\Symfony\Messenger\CommandHandlerLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

$commandToHandlerMap = [];

$messengerBus = new MessageBus([
    new HandleMessageMiddleware(new CommandHandlerLocator($commandToHandlerMap)),
]);

$commandBus = new CommandBus($messengerBus);

/** @var \Gears\CQRS\Command $command */
$commandBus->handle($command);
```

#### Asynchronicity

Simply use Symfony Messenger transports as you would with any Messenger's bus

### Query Bus

```php
use Gears\CQRS\Symfony\Messenger\QueryBus;
use Gears\CQRS\Symfony\Messenger\QueryHandlerLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

$queryToHandlerMap = [];

$messengerBus = new MessageBus([
    new HandleMessageMiddleware(new QueryHandlerLocator($queryToHandlerMap)),
]);

$queryBus = new QueryBus($messengerBus);

/** @var \Gears\CQRS\Query $query */
$result = $queryBus->handle($query);
```

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/phpgears/cqrs-symfony-messenger/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/phpgears/cqrs-symfony-messenger/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/phpgears/cqrs-symfony-messenger/blob/master/LICENSE) included with the source code for a copy of the license terms.
