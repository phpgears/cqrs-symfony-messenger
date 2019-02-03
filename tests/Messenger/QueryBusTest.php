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
use Gears\CQRS\Symfony\Messenger\Tests\Stub\DTOStub;
use Gears\CQRS\Symfony\Messenger\Tests\Stub\QueryStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Symfony Messenger query bus test.
 */
class QueryBusTest extends TestCase
{
    /**
     * @expectedException \Gears\CQRS\Symfony\Messenger\Exception\QueryReturnException
     * @expectedExceptionMessageRegExp /^Query handler for .+\\QueryStub should return an instance of Gears\\DTO\\DTO/
     */
    public function testInvalidQueryResponse(): void
    {
        $messengerMock = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messengerMock->expects($this->once())
            ->method('dispatch')
            ->will($this->returnValue(new Envelope(new \stdClass())));
        /* @var MessageBusInterface $messengerMock */

        (new QueryBus($messengerMock))->handle(QueryStub::instance());
    }

    public function testHandling(): void
    {
        $messengerMock = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messengerMock->expects($this->once())
            ->method('dispatch')
            ->will($this->returnValue(new Envelope(DTOStub::instance())));
        /* @var MessageBusInterface $messengerMock */

        (new QueryBus($messengerMock))->handle(QueryStub::instance());
    }
}
