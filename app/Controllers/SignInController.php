<?php

    namespace App\Controllers;


    use Yeager\Framework\Authentication\ISessionAuthentication;
    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\RedirectResponse;
    use Yeager\Framework\HTTP\Response;
    use App\Forms\User\SignInForm;

    class SignInController extends BaseController
    {
        private ISessionAuthentication $sessionAuthentication;

        public function __construct(ISessionAuthentication $sessionAuthentication)
        {
            $this->sessionAuthentication = $sessionAuthentication;
        }

        public function index(): Response
        {
            $title = "Sign In page";

            return $this->render("sign-in.html.twig", compact("title"));
        }

        public function store(): Response
        {
            $form = new SignInForm();

            $form->setFields(
                $this->request->input("email"),
                $this->request->input("password"),
            );

            if ($form->hasValidationErrors()) {
                foreach ($form->getValidationErrors() as $error) {
                    $this->request->getSession()->setFlash("error", $error);
                }

                return new RedirectResponse("/sign-in");
            }

            $isAuth = $this->sessionAuthentication->authenticate(
                $this->request->input("email"),
                $this->request->input("password")
            );

            if (!$isAuth) {
                $this->request->getSession()->setFlash("error", "Неверный логин или пароль");

                return new RedirectResponse("/sign-in");
            }

            $this->request->getSession()->setFlash("success", "Вход выполнен успешно");

            return new RedirectResponse("/cabinet");
        }

        public function logout(): Response
        {
            $this->sessionAuthentication->logout();

            return new RedirectResponse("/sign-in");
        }
    }