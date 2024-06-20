<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use Yeager\Framework\HTTP\Middleware\IMiddleware;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;
    use Yeager\Framework\Session\ISession;

    class StartSession implements IMiddleware
    {
        private ISession $session;

        public function __construct(ISession $session)
        {
            $this->session = $session;
        }

        public function process(Request $request, IRequestHandler $handler): Response
        {
            $this->session->start();

            $request->setSession($this->session);

            return $handler->handle($request);
        }
    }