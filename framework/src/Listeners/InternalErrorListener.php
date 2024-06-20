<?php

    namespace Yeager\Framework\Listeners;

    use Yeager\Framework\HTTP\Events\ResponseEvent;

    class InternalErrorListener
    {
        public function __invoke(ResponseEvent $event): void
        {
            if ($event->getResponse()->getStatusCode() >= 500) {
                $event->stopPropagation();
            }
        }
    }