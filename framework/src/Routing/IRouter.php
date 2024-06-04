<?php

    namespace Yeager\Framework\Routing;

    use League\Container\Container;
    use Yeager\Framework\HTTP\Request;

    interface IRouter
    {
        public function dispatch(Request $request, Container $container): array;

        public function registerRoutes(array $routes): void;
    }