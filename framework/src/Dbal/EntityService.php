<?php

    namespace Yeager\Framework\Dbal;

    use Doctrine\DBAL\Connection;
    use Yeager\Framework\Dbal\Event\EntityPersist;
    use Yeager\Framework\Event\EventDispatcher;

    class EntityService
    {
        private Connection $connection;
        private EventDispatcher $eventDispatcher;

        public function __construct(Connection $connection, EventDispatcher $eventDispatcher)
        {
            $this->connection = $connection;
            $this->eventDispatcher = $eventDispatcher;
        }

        public function getConnection(): Connection
        {
            return $this->connection;
        }

        public function getEventDispatcher(): EventDispatcher
        {
            return $this->eventDispatcher;
        }

        public function save(Entity $entity): int
        {
            $entityId = $this->connection->lastInsertId();

            $entity->setId($entityId);

            $this->eventDispatcher->dispatch(new EntityPersist($entity));

            return $entityId;
        }
    }