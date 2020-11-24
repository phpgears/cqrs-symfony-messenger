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

use Gears\CQRS\Exception\InvalidCommandHandlerException;
use Gears\CQRS\Symfony\Messenger\ContainerAwareCommandHandlerLocator;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\CommandHandlerStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\CommandStub;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

/**
 * PSR container aware command handler locator test.
 */
class ContainerAwareCommandHandlerLocatorTest extends TestCase
{
    public function testInvalidCommandHandler(): void
    {
        $this->expectException(InvalidCommandHandlerException::class);
        $this->expectExceptionMessage(
            'Command handler must implement Gears\CQRS\CommandHandler interface, string given.'
        );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(static::once())
            ->method('get')
            ->with('handler')
            ->willReturn('');
        /* @var ContainerInterface $container */

        $commandMap = [CommandStub::class => ['handler']];
        $locator = new ContainerAwareCommandHandlerLocator($container, $commandMap);
        $envelope = new Envelope(CommandStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testCommandHandler(): void
    {
        $commandHandler = new CommandHandlerStub();

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(static::once())
            ->method('get')
            ->with('handler')
            ->willReturn($commandHandler);
        /* @var ContainerInterface $container */

        $command = CommandStub::instance();

        $commandMap = [CommandStub::class => ['handler']];
        $locator = new ContainerAwareCommandHandlerLocator($container, $commandMap);
        $envelope = new Envelope(CommandStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            static::assertInstanceOf(HandlerDescriptor::class, $handler);
            static::assertNull($handler->getHandler()($command));
        }
    }
}
