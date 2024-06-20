<?php

    namespace Yeager\Framework\HTTP;

    use Yeager\Framework\Session\ISession;

    class Request
    {
        private readonly array $GET;
        private readonly array $POST;
        private readonly array $COOKIES;
        private readonly array $FILES;
        private readonly array $SERVER;
        private ISession $session;
        private mixed $routeHandler;
        private array $routeArguments;


        private function __construct(array $GET, array $POST, array $COOKIES, array $FILES, array $SERVER)
        {
            $this->GET = $GET;
            $this->POST = $POST;
            $this->COOKIES = $COOKIES;
            $this->FILES = $FILES;
            $this->SERVER = $SERVER;
        }

        public static function createFromGlobals(): static
        {
            return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
        }

        public function getPath(): string
        {
            return strtok($this->SERVER["REQUEST_URI"], "?");
        }

        public function getMethod(): string
        {
            return $this->SERVER["REQUEST_METHOD"];
        }

        public function getSession(): ISession
        {
            return $this->session;
        }

        public function setSession(ISession $session): void
        {
            $this->session = $session;
        }

        public function input(string $key, mixed $default = null)
        {
            return $this->POST[$key] ?? $default;
        }

        public function getRouteHandler(): mixed
        {
            return $this->routeHandler;
        }

        public function setRouteHandler(mixed $routeHandler): void
        {
            $this->routeHandler = $routeHandler;
        }

        public function getRouteArguments(): array
        {
            return $this->routeArguments;
        }

        public function setRouteArguments(array $routeArguments): void
        {
            $this->routeArguments = $routeArguments;
        }
    }