<?php

    namespace Yeager\Framework\Template;

    use Twig\Environment;
    use Twig\Extension\DebugExtension;
    use Twig\Loader\FilesystemLoader;
    use Twig\TwigFunction;
    use Yeager\Framework\Authentication\ISessionAuthentication;
    use Yeager\Framework\Session\ISession;

    class TwigFactory
    {
        private string $VIEWS_PATH;
        private ISession $session;
        private ISessionAuthentication $sessionAuthentication;

        public function __construct(string $VIEWS_PATH, ISession $session, ISessionAuthentication $sessionAuthentication)
        {
            $this->VIEWS_PATH = $VIEWS_PATH;
            $this->session = $session;
            $this->sessionAuthentication = $sessionAuthentication;
        }

        public function create(): Environment
        {
            $loader = new FilesystemLoader($this->VIEWS_PATH);

            $twig = new Environment($loader, [
                "debug" => true,
                "cache" => false
            ]);

            $twig->addExtension(new DebugExtension());
            $twig->addFunction(new TwigFunction("session", [$this, "getSession"]));
            $twig->addFunction(new TwigFunction("auth", [$this, "getSessionAuthentication"]));

            return $twig;
        }

        public function getSession(): ISession
        {
            return $this->session;
        }

        public function getSessionAuthentication(): ISessionAuthentication
        {
            return $this->sessionAuthentication;
        }
    }