<?php

    namespace Yeager\Framework\Dbal\Event;

    use Yeager\Framework\Dbal\Entity;
    use Yeager\Framework\Event\Event;

    class EntityPersist extends Event
    {
        private Entity $entity;

        public function __construct(Entity $entity)
        {
            $this->entity = $entity;
        }

        public function getEntity(): Entity
        {
            return $this->entity;
        }
    }