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

use Gears\CQRS\Symfony\Messenger\QueryBus;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * Symfony Messenger query bus test.
 */
class QueryBusTest extends TestCase
{
    public function testHandling(): void
    {
        $returnEnvelope = (new Envelope(QueryStub::instance()))
            ->with(HandledStamp::fromDescriptor(new HandlerDescriptor('strlen'), 'return'));

        $messengerMock = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messengerMock->expects(static::once())
            ->method('dispatch')
            ->willReturn($returnEnvelope);
        /* @var MessageBusInterface $messengerMock */

        static::assertSame('return', (new QueryBus($messengerMock))->handle(QueryStub::instance()));
    }
}
