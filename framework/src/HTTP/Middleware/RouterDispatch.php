<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use League\Container\Container;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;
    use Yeager\Framework\Routing\IRouter;

    class RouterDispatch implements IMiddleware
    {
        private IRouter $router;
        private Container $container;

        public function __construct(IRouter $router, Container $container)
        {
            $this->router = $router;
            $this->container = $container;
        }

        public function process(Request $request, IRequestHandler $handler): Response
        {
            [$routeHandler, $variables] = $this->router->dispatch($request, $this->container);

            return call_user_func_array($routeHandler, $variables);
        }
    }