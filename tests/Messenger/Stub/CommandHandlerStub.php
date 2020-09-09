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

namespace Gears\CQRS\Symfony\Messenger\Tests\Stub;

use Gears\CQRS\AbstractCommandHandler;

/**
 * Command handler stub class.
 */
class CommandHandlerStub extends AbstractCommandHandler
{
    /**
     * {@inheritdoc}
     */
    protected function getSupportedCommandTypes(): array
    {
        return [CommandStub::class];
    }

    /**
     * @param CommandStub $command
     */
    protected function handleCommandStub(CommandStub $command): void
    {
    }
}
