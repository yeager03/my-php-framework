<?php

    namespace Yeager\Framework\Console\Commands;

    use Doctrine\DBAL\Exception;
    use Throwable;
    use Yeager\Framework\Console\ICommand;
    use Doctrine\DBAL\Connection;
    use Doctrine\DBAL\Schema\Schema;
    use Doctrine\DBAL\Types\Types;

    class MigrateCommand implements ICommand
    {
        private string $command_name = "migrate";
        private const MIGRATIONS_TABLE = "migrations";
        private Connection $connection;

        private string $MIGRATIONS_PATH;

        public function __construct(Connection $connection, string $MIGRATIONS_PATH)
        {
            $this->connection = $connection;
            $this->MIGRATIONS_PATH = $MIGRATIONS_PATH;
        }


        /**
         * @throws Throwable
         * @throws Exception
         */
        public function execute(array $parameters = []): int
        {
            try {

                $this->createMigrationsTable();


                $appliedMigrations = $this->getAppliedMigrations();

                $migrationFiles = $this->getMigrationFiles();

                $migrationsToApply = array_values(array_diff($migrationFiles, $appliedMigrations));

                $schema = new Schema();

                foreach ($migrationsToApply as $migration) {
                    $migrationInstance = require_once $this->MIGRATIONS_PATH . "/{$migration}";

                    $migrationInstance->up($schema);

                    $this->addMigration($migration);
                }

                $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

                foreach ($sqlArray as $sql) {
                    $this->connection->executeQuery($sql);
                }

            } catch (Throwable $exception) {
                throw $exception;
            }

            return 0;
        }

        private function createMigrationsTable(): void
        {
            $schemaManager = $this->connection->createSchemaManager();

            if (!$schemaManager->tablesExist(self::MIGRATIONS_TABLE)) {
                $schema = new Schema();

                $table = $schema->createTable(self::MIGRATIONS_TABLE);
                $table->addColumn("id", Types::INTEGER, [
                    "unsigned" => true,
                    "autoincrement" => true
                ]);
                $table->addColumn("migration", Types::STRING);
                $table->addColumn("created_at", Types::DATETIME_IMMUTABLE, [
                    "default" => "CURRENT_TIMESTAMP"
                ]);
                $table->setPrimaryKey(["id"]);

                $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

                $this->connection->executeQuery($sqlArray[0]);

                echo "Migrations table created" . PHP_EOL;
            }
        }

        private function getAppliedMigrations(): array
        {
            $queryBuilder = $this->connection->createQueryBuilder();

            return $queryBuilder
                ->select("migration")
                ->from(self::MIGRATIONS_TABLE)
                ->executeQuery()
                ->fetchFirstColumn();
        }

        private function getMigrationFiles(): array
        {
            $migrationFiles = scandir($this->MIGRATIONS_PATH);

            $filteredFiles = array_filter($migrationFiles, function ($fileName) {
                return !in_array($fileName, [".", ".."]);
            });

            return array_values($filteredFiles);
        }

        private function addMigration(string $migration): void
        {
            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder->insert(self::MIGRATIONS_TABLE)
                ->values(["migration" => ":migration"])
                ->setParameter("migration", $migration)
                ->executeQuery();
        }
    }