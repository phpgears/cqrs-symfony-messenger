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

use Gears\CQRS\QueryHandler;
use Gears\CQRS\Symfony\Messenger\Exception\InvalidQueryHandlerException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;

class ContainerAwareQueryHandlerLocator extends QueryHandlerLocator
{
    /**
     * PSR container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * ContainerAwareCommandHandlerLocator constructor.
     *
     * @param ContainerInterface $container
     * @param mixed[]            $handlers
     */
    public function __construct(ContainerInterface $container, array $handlers)
    {
        $this->container = $container;

        parent::__construct($handlers);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidQueryHandlerException
     */
    public function getHandlersMap(Envelope $envelope): iterable
    {
        $seen = [];

        foreach ($this->getQueryMap($envelope) as $type) {
            foreach ($this->handlersMap[$type] ?? [] as $alias => $handler) {
                $handler = $this->container->get($handler);

                if (!$handler instanceof QueryHandler) {
                    throw new InvalidQueryHandlerException(\sprintf(
                        'Query handler must implement %s interface, %s given',
                        QueryHandler::class,
                        \is_object($handler) ? \get_class($handler) : \gettype($handler)
                    ));
                }

                if (!\in_array($handler, $seen, true)) {
                    yield $alias => $seen[] = $handler;
                }
            }
        }
    }
}
