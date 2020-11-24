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
use Gears\CQRS\CommandHandler;
use Gears\CQRS\Exception\InvalidCommandHandlerException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

class ContainerAwareCommandHandlerLocator extends CommandHandlerLocator
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
     * @throws InvalidCommandHandlerException
     */
    public function getHandlers(Envelope $envelope): iterable
    {
        $seen = [];

        foreach ($this->getCommandMap($envelope) as $type) {
            foreach ($this->handlersMap[$type] ?? [] as $alias => $handler) {
                $handler = $this->container->get($handler);

                if (!$handler instanceof CommandHandler) {
                    throw new InvalidCommandHandlerException(\sprintf(
                        'Command handler must implement %s interface, %s given.',
                        CommandHandler::class,
                        \is_object($handler) ? \get_class($handler) : \gettype($handler)
                    ));
                }

                $handlerCallable = function (Command $command) use ($handler): void {
                    $handler->handle($command);
                };

                if (!\in_array($handlerCallable, $seen, true)) {
                    $seen[] = $handlerCallable;

                    yield $alias => new HandlerDescriptor($handlerCallable);
                }
            }
        }
    }
}
