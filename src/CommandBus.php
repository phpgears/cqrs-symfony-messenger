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

namespace Gears\CQRS\Symfony\Messenger;

use Gears\CQRS\Command;
use Gears\CQRS\CommandBus as CommandBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBus implements CommandBusInterface
{
    /**
     * Wrapped message bus.
     *
     * @var MessageBusInterface
     */
    private $wrappedMessageBus;

    /**
     * CommandBus constructor.
     *
     * @param MessageBusInterface $wrappedMessageBus
     */
    public function __construct(MessageBusInterface $wrappedMessageBus)
    {
        $this->wrappedMessageBus = $wrappedMessageBus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Command $command): void
    {
        $this->wrappedMessageBus->dispatch(new Envelope($command));
    }
}
