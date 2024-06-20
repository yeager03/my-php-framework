<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;

    interface IRequestHandler
    {
        public function handle(Request $request): Response;

        public function injectMiddlewares(array $middlewares): void;
    }