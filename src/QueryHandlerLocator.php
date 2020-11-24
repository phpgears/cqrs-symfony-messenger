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

use Gears\CQRS\Exception\InvalidQueryException;
use Gears\CQRS\Exception\InvalidQueryHandlerException;
use Gears\CQRS\Query;
use Gears\CQRS\QueryHandler;
use Gears\DTO\DTO;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;

class QueryHandlerLocator implements HandlersLocatorInterface
{
    /**
     * Query handlers map.
     *
     * @var mixed[]
     */
    protected $handlersMap;

    /**
     * QueryHandlerLocator constructor.
     *
     * @param mixed[] $handlers
     */
    public function __construct(array $handlers)
    {
        $handlers = \array_map(
            function ($handler) {
                if (!\is_array($handler)) {
                    $handler = [$handler];
                }

                if (\count($handler) !== 1) {
                    throw new InvalidQueryHandlerException(\sprintf(
                        'Only one query handler allowed, %s given.',
                        \count($handler)
                    ));
                }

                return $handler;
            },
            $handlers
        );

        $this->handlersMap = $handlers;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidQueryHandlerException
     */
    public function getHandlers(Envelope $envelope): iterable
    {
        $seen = [];

        foreach ($this->getQueryMap($envelope) as $type) {
            foreach ($this->handlersMap[$type] ?? [] as $alias => $handler) {
                if (!$handler instanceof QueryHandler) {
                    throw new InvalidQueryHandlerException(\sprintf(
                        'Query handler must implement "%s" interface, "%s" given.',
                        QueryHandler::class,
                        \is_object($handler) ? \get_class($handler) : \gettype($handler)
                    ));
                }

                $handlerCallable = function (Query $query) use ($handler): DTO {
                    return $handler->handle($query);
                };

                if (!\in_array($handlerCallable, $seen, true)) {
                    $seen[] = $handlerCallable;

                    yield $alias => new HandlerDescriptor($handlerCallable);
                }
            }
        }
    }

    /**
     * Get command mapping.
     *
     * @param Envelope $envelope
     *
     * @throws InvalidQueryException
     *
     * @return mixed[]
     */
    final protected function getQueryMap(Envelope $envelope): array
    {
        /** @var mixed $query */
        $query = $envelope->getMessage();

        if (!$query instanceof Query) {
            throw new InvalidQueryException(\sprintf(
                'Query must implement "%s" interface, "%s" given.',
                Query::class,
                \is_object($query) ? \get_class($query) : \gettype($query)
            ));
        }

        $type = $query->getQueryType();

        return [$type => $type]
            + ['*' => '*'];
    }
}
