<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;

    interface IMiddleware
    {
        public function process(Request $request, IRequestHandler $handler): Response;
    }