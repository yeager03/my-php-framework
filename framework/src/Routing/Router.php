<?php

    namespace Yeager\Framework\Routing;

    use FastRoute\Dispatcher;
    use FastRoute\RouteCollector;
    use League\Container\Container;
    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\Exceptions\MethodNotAllowedException;
    use Yeager\Framework\HTTP\Exceptions\RouteNotFoundException;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\Routing\IRouter;
    use function FastRoute\simpleDispatcher;

    class Router implements IRouter
    {
        private array $routes;

        public function registerRoutes(array $routes): void
        {
            $this->routes = $routes;
        }

        /**
         * @throws RouteNotFoundException
         * @throws MethodNotAllowedException
         */
        public function dispatch(Request $request, Container $container): array
        {
            [$handler, $variables] = $this->extractRouteInformation($request);

            if (is_array($handler)) {
                [$controllerId, $method] = $handler;
                $controller = $container->get($controllerId);

                if (is_subclass_of($controller, BaseController::class)) {
                    $controller->setRequest($request);
                }

                $handler = [$controller, $method];
            }

            return [$handler, $variables];
        }

        /**
         * @throws MethodNotAllowedException
         * @throws RouteNotFoundException
         */
        private function extractRouteInformation(Request $request): array
        {
            $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
                foreach ($this->routes as $route) {
                    $collector->addRoute(...$route);
                }
            });

            $routeInformation = $dispatcher->dispatch(
                $request->getMethod(),
                $request->getPath(),
            );

            switch ($routeInformation[0]) {
                case Dispatcher::FOUND:
                    return [$routeInformation[1], $routeInformation[2]];
                case Dispatcher::METHOD_NOT_ALLOWED:
                    $allowed_methods = implode(", ", $routeInformation[1]);
                    $exception = new MethodNotAllowedException("Supported HTTP methods: {$allowed_methods}");
                    $exception->setStatusCode(405);

                    throw $exception;
                default:
                    $exception = new RouteNotFoundException("Route not found");
                    $exception->setStatusCode(404);

                    throw $exception;
            }
        }


    }