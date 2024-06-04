<?php

    namespace Yeager\Framework\HTTP\Exceptions;

    class HTTPException extends \Exception
    {
        private int $statusCode = 400;

        public function getStatusCode(): int
        {
            return $this->statusCode;
        }

        public function setStatusCode(int $statusCode): void
        {
            $this->statusCode = $statusCode;
        }
    }