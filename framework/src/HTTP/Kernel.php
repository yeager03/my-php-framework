<?php

    namespace Yeager\Framework\HTTP;

    use Exception;
    use League\Container\Container;
    use Yeager\Framework\Event\EventDispatcher;
    use Yeager\Framework\HTTP\Events\ResponseEvent;
    use Yeager\Framework\HTTP\Middleware\IRequestHandler;
    use Yeager\Framework\HTTP\Exceptions\HTTPException;

    class Kernel
    {
        private readonly Container $container;
        private readonly IRequestHandler $requestHandler;
        private readonly EventDispatcher $eventDispatcher;
        private string $environment;

        public function __construct(Container $container, IRequestHandler $requestHandler, EventDispatcher $eventDispatcher)
        {
            $this->container = $container;
            $this->requestHandler = $requestHandler;
            $this->eventDispatcher = $eventDispatcher;

            $this->environment = $this->container->get("APP_ENV");
        }

        public function handle(Request $request): Response
        {
            try {
                $response = $this->requestHandler->handle($request);
            } catch (Exception $exception) {
                $response = $this->createExceptionResponse($exception);
            }

            $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));

            return $response;
        }

        public function terminate(Request $request): void
        {
            $request->getSession()?->clearFlash();
        }

        /**
         * @throws Exception
         */
        private function createExceptionResponse(Exception $exception): Response
        {
            if (in_array($this->environment, ["local", "dev", "development"])) {
                throw $exception;
            }

            if ($exception instanceof HTTPException) {
                return new Response($exception->getMessage(), $exception->getStatusCode());
            }

            return new Response("Server error", 500);
        }
    }