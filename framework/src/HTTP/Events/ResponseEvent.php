<?php

    namespace Yeager\Framework\HTTP\Events;

    use Yeager\Framework\Event\Event;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;

    class ResponseEvent extends Event
    {
        private Request $request;
        private Response $response;

        public function __construct(Request $request, Response $response)
        {
            $this->request = $request;
            $this->response = $response;
        }

        public function getRequest(): Request
        {
            return $this->request;
        }

        public function getResponse(): Response
        {
            return $this->response;
        }
    }