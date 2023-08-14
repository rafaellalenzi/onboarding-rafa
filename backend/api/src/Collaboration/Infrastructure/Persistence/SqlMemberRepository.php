<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Infrastructure\Persistence;

use Doctrine\DBAL\Driver\Connection;

class SqlMemberRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
