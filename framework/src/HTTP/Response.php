<?php

    namespace Yeager\Framework\HTTP;

    class Response
    {
        private mixed $content;
        private int $statusCode;
        private array $headers;

        public function __construct(mixed $content, int $statusCode = 200, array $headers = [])
        {
            $this->content = $content;
            $this->statusCode = $statusCode;
            $this->headers = $headers;

            http_response_code($this->statusCode);
        }

        public function send(): void
        {
            echo $this->content;
        }
    }