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

use Gears\CQRS\Exception\QueryReturnException;
use Gears\CQRS\Query;
use Gears\CQRS\QueryBus as QueryBusInterface;
use Gears\DTO\DTO;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

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
     *
     * @throws QueryReturnException
     */
    public function handle(Query $query): DTO
    {
        $dto = $this->wrappedMessageBus->dispatch(new Envelope($query))->getMessage();

        if (!$dto instanceof DTO) {
            throw new QueryReturnException(\sprintf(
                'Query handler for %s should return an instance of %s',
                \get_class($query),
                DTO::class
            ));
        }

        return $dto;
    }
}
