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

use Gears\CQRS\Query;
use Gears\CQRS\QueryBus as QueryBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QueryBus implements QueryBusInterface
{
    /**
     * Wrapped command bus.
     *
     * @var MessageBusInterface
     */
    private $wrappedMessageBus;

    /**
     * QueryBus constructor.
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
    public function handle(Query $query)
    {
        /** @var HandledStamp $handlerResult */
        $handlerResult = $this->wrappedMessageBus->dispatch(new Envelope($query))
            ->last(HandledStamp::class);

        return $handlerResult->getResult();
    }
}
