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

use Gears\CQRS\Symfony\Messenger\CommandHandlerLocator;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\CommandHandlerStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\CommandStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

/**
 * Command handler locator test.
 */
class CommandHandlerLocatorTest extends TestCase
{
    /**
     * @expectedException \Gears\CQRS\Exception\InvalidCommandException
     * @expectedExceptionMessage Command must implement Gears\CQRS\Command interface, stdClass given
     */
    public function testInvalidCommand(): void
    {
        $envelope = new Envelope(new \stdClass());

        foreach ((new CommandHandlerLocator([]))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    /**
     * @expectedException \Gears\CQRS\Exception\InvalidCommandHandlerException
     * @expectedExceptionMessage Only one command handler allowed, 2 given
     */
    public function testInvalidCommandHandlersCount(): void
    {
        $commandMap = [CommandStub::class => ['', '']];
        $envelope = new Envelope(new \stdClass());

        foreach ((new CommandHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    /**
     * @expectedException \Gears\CQRS\Exception\InvalidCommandHandlerException
     * @expectedExceptionMessage Command handler must implement Gears\CQRS\CommandHandler interface, string given
     */
    public function testInvalidCommandHandler(): void
    {
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
            $this->assertInstanceOf(\Closure::class, $handler);
        }
    }
}
