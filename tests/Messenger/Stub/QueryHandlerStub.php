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

use Gears\CQRS\AbstractQueryHandler;
use Gears\CQRS\Query;
use Gears\DTO\DTO;

/**
 * Query handler stub class.
 */
class QueryHandlerStub extends AbstractQueryHandler
{
    /**
     * {@inheritdoc}
     */
    protected function getSupportedQueryType(): string
    {
        return QueryStub::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function handleQuery(Query $query): DTO
    {
        return DTOStub::instance();
    }
}
