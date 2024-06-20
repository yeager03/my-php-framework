<?php

    namespace Yeager\Framework\HTTP\Middleware;

    use Yeager\Framework\Authentication\ISessionAuthentication;
    use Yeager\Framework\HTTP\RedirectResponse;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Response;
    use Yeager\Framework\Session\ISession;

    class Authenticate implements IMiddleware
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

            if (!$this->sessionAuthentication->check()) {
                $this->session->setFlash("error", "To get started, you need to sign in to your account.");

                return new RedirectResponse("/sign-in");
            }

            return $handler->handle($request);
        }

    }