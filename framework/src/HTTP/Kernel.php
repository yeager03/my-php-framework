<?php

    namespace Yeager\Framework\HTTP;

    use Exception;
    use League\Container\Container;
    use Yeager\Framework\Routing\IRouter;
    use Yeager\Framework\HTTP\Exceptions\HTTPException;

    class Kernel
    {
        private IRouter $router;
        private Container $container;

        private string $environment;

        public function __construct(IRouter $router, Container $container)
        {
            $this->router = $router;
            $this->container = $container;

            $this->environment = $this->container->get("APP_ENV");
        }

        public function handle(Request $request): Response
        {
            try {
                [$routeHandler, $variables] = $this->router->dispatch($request, $this->container);

                $response = call_user_func_array($routeHandler, $variables);
            } catch (Exception $exception) {
                $response = $this->createExceptionResponse($exception);
            }

            return $response;
        }


        /**
         * @throws Exception
         */
        public function createExceptionResponse(Exception $exception): Response
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