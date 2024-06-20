<?php

    namespace Yeager\Framework\Routing;

    class Route
    {
        public static function get(string $uri, array|callable $handler, array $middlewares = []): array
        {
            return ["GET", $uri, [$handler, $middlewares]];
        }

        public static function post(string $uri, array|callable $handler, array $middlewares = []): array
        {
            return ["POST", $uri, [$handler, $middlewares]];
        }
    }