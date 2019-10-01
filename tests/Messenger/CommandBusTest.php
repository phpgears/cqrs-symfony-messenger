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

use Gears\CQRS\Symfony\Messenger\CommandBus;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\CommandStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Symfony Messenger command bus test.
 */
class CommandBusTest extends TestCase
{
    public function testHandling(): void
    {
        $messengerMock = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messengerMock->expects(static::once())
            ->method('dispatch')
            ->will(static::returnValue(new Envelope(new \stdClass())));
        /* @var MessageBusInterface $messengerMock */

        (new CommandBus($messengerMock))->handle(CommandStub::instance());
    }
}
