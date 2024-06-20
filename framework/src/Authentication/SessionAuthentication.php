<?php

    namespace Yeager\Framework\Authentication;

    use Yeager\Framework\Session\ISession;
    use Yeager\Framework\Session\Session;

    class SessionAuthentication implements ISessionAuthentication
    {
        private IAuthUser $authUser;
        private IUserService $userService;
        private ISession $session;

        public function __construct(IUserService $userService, ISession $session)
        {
            $this->userService = $userService;
            $this->session = $session;
        }

        public function authenticate(string $email, string $password): bool
        {
            $user = $this->userService->findByEmail($email);

            if (!$user) {
                return false;
            }

            if (password_verify($password, $user->getPassword())) {
                $this->login($user);

                return true;
            }

            return false;
        }

        public function login(IAuthUser $user): void
        {
            $this->session->set(Session::AUTH_KEY, $user->getId());

            $this->authUser = $user;
        }

        public function logout(): void
        {
            $this->session->remove(Session::AUTH_KEY);
        }

        public function getUser(): IAuthUser
        {
            return $this->authUser;
        }

        public function check(): bool
        {
            return $this->session->has(Session::AUTH_KEY);
        }
    }