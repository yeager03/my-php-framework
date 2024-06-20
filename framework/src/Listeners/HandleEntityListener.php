<?php

    namespace Yeager\Framework\Listeners;

    use Yeager\Framework\Dbal\EntityService;
    use Yeager\Framework\Dbal\Event\EntityPersist;

    class HandleEntityListener
    {
        public function __invoke(EntityPersist $event)
        {
//            dd($event->getEntity());
        }
    }