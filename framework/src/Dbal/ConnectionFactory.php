<?php

    namespace Yeager\Framework\Dbal;

    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\DriverManager;
    use Doctrine\DBAL\Exception;

    class ConnectionFactory
    {
        private readonly string $DATABASE_URL;

        public function __construct(string $DATABASE_URL)
        {
            $this->DATABASE_URL = $DATABASE_URL;
        }

        /**
         * @throws Exception
         */
        public function create(): Connection
        {
            return DriverManager::getConnection([
                "url" => $this->DATABASE_URL
            ]);
        }
    }