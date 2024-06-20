<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use Yeager\Framework\Authentication\ISessionAuthentication;
    use Yeager\Framework\HTTP\RedirectResponse;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;
    use Yeager\Framework\Session\ISession;

    class Guest implements IMiddleware
    {
        private ISessionAuthentication $sessionAuthentication;
        private ISession $session;

        public function __construct(ISessionAuthentication $sessionAuthentication, ISession $session)
        {
            $this->sessionAuthentication = $sessionAuthentication;
            $this->session = $session;
        }

        public function process(Request $request, IRequestHandler $handler): Response
        {
            $this->session->start();

            if ($this->sessionAuthentication->check()) {
                return new RedirectResponse("/cabinet");
            }

            return $handler->handle($request);
        }

    }