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

use Gears\CQRS\Exception\InvalidQueryHandlerException;
use Gears\CQRS\Symfony\Messenger\ContainerAwareQueryHandlerLocator;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryHandlerStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryStub;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

/**
 * PSR container aware query handler locator test.
 */
class ContainerAwareQueryHandlerLocatorTest extends TestCase
{
    public function testInvalidCommandHandler(): void
    {
        $this->expectException(InvalidQueryHandlerException::class);
        $this->expectExceptionMessage('Query handler must implement Gears\CQRS\QueryHandler interface, string given');

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(static::once())
            ->method('get')
            ->with('handler')
            ->willReturn('');
        /* @var ContainerInterface $container */

        $queryMap = [QueryStub::class => ['handler']];
        $locator = new ContainerAwareQueryHandlerLocator($container, $queryMap);
        $envelope = new Envelope(QueryStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testHandler(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(static::once())
            ->method('get')
            ->with('handler')
            ->willReturn(new QueryHandlerStub());
        /* @var ContainerInterface $container */

        $query = QueryStub::instance();

        $queryMap = [QueryStub::class => ['handler']];
        $locator = new ContainerAwareQueryHandlerLocator($container, $queryMap);
        $envelope = new Envelope(QueryStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            static::assertInstanceOf(HandlerDescriptor::class, $handler);
            static::assertTrue($handler->getHandler()($query));
        }
    }
}
