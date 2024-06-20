<?php

    namespace Yeager\Framework\HTTP;

    use Doctrine\DBAL\Connection;
    use Exception;
    use League\Container\Container;
    use Yeager\Framework\HTTP\Middleware\IRequestHandler;
    use Yeager\Framework\Routing\IRouter;
    use Yeager\Framework\HTTP\Exceptions\HTTPException;

    class Kernel
    {
        private IRouter $router;
        private Container $container;
        private IRequestHandler $requestHandler;
        private string $environment;

        public function __construct(IRouter $router, Container $container, IRequestHandler $requestHandler)
        {
            $this->router = $router;
            $this->container = $container;
            $this->requestHandler = $requestHandler;

            $this->environment = $this->container->get("APP_ENV");
        }

        public function handle(Request $request): Response
        {
            try {
                $response = $this->requestHandler->handle($request);

//                [$routeHandler, $variables] = $this->router->dispatch($request, $this->container);
//
//                $response = call_user_func_array($routeHandler, $variables);
            } catch (Exception $exception) {
                $response = $this->createExceptionResponse($exception);
            }

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