<?php

    namespace Yeager\Framework\HTTP;

    class Request
    {
        private readonly array $GET;
        public readonly array $POST;
        private readonly array $COOKIES;
        private readonly array $FILES;
        private readonly array $SERVER;


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
    }