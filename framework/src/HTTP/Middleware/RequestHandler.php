<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use League\Container\Container;
    use Yeager\Framework\HTTP\Middleware\IRequestHandler;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;

    class RequestHandler implements IRequestHandler
    {
        private Container $container;

        private array $middlewares = [
            ExtractRouteInfo::class,
            StartSession::class,
            RouterDispatch::class
        ];


        public function __construct(Container $container)
        {
            $this->container = $container;
        }

        public function handle(Request $request): Response
        {
            if (empty($this->middlewares)) {
                return new Response("Server error", 500);
            }

            $middlewareClass = array_shift($this->middlewares);

            $middleware = $this->container->get($middlewareClass);

            return $middleware->process($request, $this);
        }

        public function injectMiddlewares(array $middlewares): void
        {
            array_splice($this->middlewares, 0, 0, $middlewares);
        }
    }