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

use Gears\CQRS\Exception\InvalidCommandException;
use Gears\CQRS\Exception\InvalidCommandHandlerException;
use Gears\CQRS\Symfony\Messenger\CommandHandlerLocator;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\CommandHandlerStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\CommandStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

/**
 * Command handler locator test.
 */
class CommandHandlerLocatorTest extends TestCase
{
    public function testInvalidCommand(): void
    {
        $this->expectException(InvalidCommandException::class);
        $this->expectExceptionMessage('Command must implement "Gears\CQRS\Command" interface, "stdClass" given');

        $envelope = new Envelope(new \stdClass());

        foreach ((new CommandHandlerLocator([]))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testInvalidCommandHandlersCount(): void
    {
        $this->expectException(InvalidCommandHandlerException::class);
        $this->expectExceptionMessage('Only one command handler allowed, 2 given');

        $commandMap = [CommandStub::class => ['', '']];
        $envelope = new Envelope(new \stdClass());

        foreach ((new CommandHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testInvalidCommandHandler(): void
    {
        $this->expectException(InvalidCommandHandlerException::class);
        $this->expectExceptionMessage(
            'Command handler must implement "Gears\CQRS\CommandHandler" interface, "string" given'
        );

        $commandMap = [CommandStub::class => ['']];
        $envelope = new Envelope(CommandStub::instance());

        foreach ((new CommandHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testCommandHandler(): void
    {
        $commandHandler = new CommandHandlerStub();
        $commandMap = [CommandStub::class => $commandHandler];
        $envelope = new Envelope(CommandStub::instance());

        foreach ((new CommandHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            static::assertInstanceOf(HandlerDescriptor::class, $handler);
        }
    }
}
