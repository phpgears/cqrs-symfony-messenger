<?php

/*
 * cqrs-symfony-messenger (https://github.com/phpgears/cqrs-symfony-messenger).
 * CQRS implementation with Symfony's Messenger.
 *
 * @license MIT
 * @link https://github.com/phpgears/cqrs-symfony-messenger
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Gears\CQRS\Symfony\Messenger\Tests;

use Gears\CQRS\Exception\InvalidQueryException;
use Gears\CQRS\Exception\InvalidQueryHandlerException;
use Gears\CQRS\Symfony\Messenger\QueryHandlerLocator;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryHandlerStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

/**
 * Query handler locator test.
 */
class QueryHandlerLocatorTest extends TestCase
{
    public function testInvalidQuery(): void
    {
        $this->expectException(InvalidQueryException::class);
        $this->expectExceptionMessage('Query must implement "Gears\CQRS\Query" interface, "stdClass" given');

        $envelope = new Envelope(new \stdClass());

        foreach ((new QueryHandlerLocator([]))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testInvalidQueryHandlersCount(): void
    {
        $this->expectException(InvalidQueryHandlerException::class);
        $this->expectExceptionMessage('Only one query handler allowed, 2 given');

        $commandMap = [QueryStub::class => ['', '']];
        $envelope = new Envelope(new \stdClass());

        foreach ((new QueryHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testInvalidQueryHandler(): void
    {
        $this->expectException(InvalidQueryHandlerException::class);
        $this->expectExceptionMessage(
            'Query handler must implement "Gears\CQRS\QueryHandler" interface, "string" given'
        );

        $commandMap = [QueryStub::class => ['']];
        $envelope = new Envelope(QueryStub::instance());

        foreach ((new QueryHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testQueryHandler(): void
    {
        $commandHandler = new QueryHandlerStub();
        $commandMap = [QueryStub::class => $commandHandler];
        $envelope = new Envelope(QueryStub::instance());

        foreach ((new QueryHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            static::assertInstanceOf(HandlerDescriptor::class, $handler);
        }
    }
}
