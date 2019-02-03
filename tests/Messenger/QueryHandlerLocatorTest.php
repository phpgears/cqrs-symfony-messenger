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

use Gears\CQRS\Symfony\Messenger\QueryHandlerLocator;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryHandlerStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

/**
 * Query handler locator test.
 */
class QueryHandlerLocatorTest extends TestCase
{
    /**
     * @expectedException \Gears\CQRS\Exception\InvalidQueryException
     * @expectedExceptionMessage Query must implement Gears\CQRS\Query interface, stdClass given
     */
    public function testInvalidQuery(): void
    {
        $envelope = new Envelope(new \stdClass());

        foreach ((new QueryHandlerLocator([]))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    /**
     * @expectedException \Gears\CQRS\Symfony\Messenger\Exception\InvalidQueryHandlerException
     * @expectedExceptionMessage Only one query handler allowed, 2 given
     */
    public function testInvalidQueryHandlersCount(): void
    {
        $commandMap = [QueryStub::class => ['', '']];
        $envelope = new Envelope(new \stdClass());

        foreach ((new QueryHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    /**
     * @expectedException \Gears\CQRS\Symfony\Messenger\Exception\InvalidQueryHandlerException
     * @expectedExceptionMessage Query handler must implement Gears\CQRS\QueryHandler interface, string given
     */
    public function testInvalidQueryHandler(): void
    {
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
            $this->assertInstanceOf(\Closure::class, $handler);
        }
    }
}
