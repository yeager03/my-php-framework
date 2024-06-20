<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use FastRoute\Dispatcher;
    use Yeager\Framework\HTTP\Middleware\IMiddleware;
    use Yeager\Framework\HTTP\Exceptions\MethodNotAllowedException;
    use Yeager\Framework\HTTP\Exceptions\RouteNotFoundException;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;
    use FastRoute\RouteCollector;
    use function FastRoute\simpleDispatcher;

    class ExtractRouteInfo implements IMiddleware
    {
        private array $routes;

        public function __construct(array $routes)
        {
            $this->routes = $routes;
        }

        public function process(Request $request, IRequestHandler $handler): Response
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
                    $request->setRouteHandler($routeInformation[1][0]);
                    $request->setRouteArguments($routeInformation[2]);
                    $handler->injectMiddlewares($routeInformation[1][1]);

                    break;
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

            return $handler->handle($request);
        }
    }