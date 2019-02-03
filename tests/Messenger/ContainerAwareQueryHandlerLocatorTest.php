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

use Gears\CQRS\Symfony\Messenger\ContainerAwareQueryHandlerLocator;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryHandlerStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryStub;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;

/**
 * PSR container aware query handler locator test.
 */
class ContainerAwareQueryHandlerLocatorTest extends TestCase
{
    /**
     * @expectedException \Gears\CQRS\Symfony\Messenger\Exception\InvalidQueryHandlerException
     * @expectedExceptionMessage Query handler must implement Gears\CQRS\QueryHandler interface, string given
     */
    public function testInvalidCommandHandler(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects($this->once())
            ->method('get')
            ->with('handler')
            ->will($this->returnValue(''));
        /* @var ContainerInterface $container */

        $queryMap = [QueryStub::class => ['handler']];
        $locator = new ContainerAwareQueryHandlerLocator($container, $queryMap);
        $envelope = new Envelope(QueryStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testQueryHandler(): void
    {
        $queryHandler = new QueryHandlerStub();

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects($this->once())
            ->method('get')
            ->with('handler')
            ->will($this->returnValue($queryHandler));
        /* @var ContainerInterface $container */

        $queryMap = [QueryStub::class => ['handler']];
        $locator = new ContainerAwareQueryHandlerLocator($container, $queryMap);
        $envelope = new Envelope(QueryStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            $this->assertSame($queryHandler, $handler);
        }
    }
}
