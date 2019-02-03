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

use Gears\CQRS\AbstractCommand;

/**
 * Command stub class.
 */
class CommandStub extends AbstractCommand
{
    /**
     * Instantiate command.
     *
     * @return self
     */
    public static function instance(): self
    {
        return new self([]);
    }
}
