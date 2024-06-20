<?php

    namespace Yeager\Framework\HTTP;

    class Response
    {
        private string $content;
        private int $statusCode;
        private array $headers;

        public function __construct(string $content = "", int $statusCode = 200, array $headers = [])
        {
            $this->content = $content;
            $this->statusCode = $statusCode;
            $this->headers = $headers;

            http_response_code($this->statusCode);
        }

        public function send(): void
        {
            ob_start();

            foreach ($this->headers as $key => $value) {
                header("{$key}: {$value}");
            }

            echo $this->content;

            ob_end_flush();
        }

        public function setContent(string $content): Response
        {
            $this->content = $content;

            return $this;
        }

        public function getHeader(string $key): string
        {
            return $this->headers[$key];
        }

        public function getStatusCode(): int
        {
            return $this->statusCode;
        }

        public function getHeaders(): array
        {
            return $this->headers;
        }

        public function setHeader(string $key, mixed $value): void
        {
            $this->headers[$key] = $value;
        }

        public function getContent(): string
        {
            return $this->content;
        }

        public function setStatusCode(int $statusCode): void
        {
            $this->statusCode = $statusCode;
        }
    }