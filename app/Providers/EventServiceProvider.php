<?php

    namespace App\Providers;

    use Yeager\Framework\Dbal\Event\EntityPersist;
    use Yeager\Framework\Event\EventDispatcher;
    use Yeager\Framework\HTTP\Events\ResponseEvent;
    use Yeager\Framework\Listeners\ContentLengthListener;
    use Yeager\Framework\Listeners\HandleEntityListener;
    use Yeager\Framework\Listeners\InternalErrorListener;
    use Yeager\Framework\Providers\IServiceProvider;

    class EventServiceProvider implements IServiceProvider
    {
        private EventDispatcher $eventDispatcher;
        private array $listen = [
            ResponseEvent::class => [
                InternalErrorListener::class,
                ContentLengthListener::class
            ],
            EntityPersist::class => [
                HandleEntityListener::class
            ]
        ];

        public function __construct(EventDispatcher $eventDispatcher)
        {
            $this->eventDispatcher = $eventDispatcher;
        }

        public function register(): void
        {
            foreach ($this->listen as $event => $listeners) {
                foreach (array_unique($listeners) as $listener) {
                    $this->eventDispatcher->addListener($event, new $listener);
                }
            }
        }
    }