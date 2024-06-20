<?php

    namespace App\Controllers;

    use App\Entities\User;
    use App\Forms\User\SignUpForm;
    use App\Services\UserService;
    use Yeager\Framework\Authentication\ISessionAuthentication;
    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\RedirectResponse;
    use Yeager\Framework\HTTP\Response;

    class SignUpController extends BaseController
    {
        private UserService $userService;
        private ISessionAuthentication $sessionAuthentication;

        public function __construct(UserService $userService, ISessionAuthentication $sessionAuthentication)
        {
            $this->userService = $userService;
            $this->sessionAuthentication = $sessionAuthentication;
        }

        public function index(): Response
        {
            $title = "Sign Up page";

            return $this->render("sign-up.html.twig", compact("title"));
        }

        public function store(): Response
        {
            $form = new SignUpForm($this->userService);

            $form->setFields(
                $this->request->input("email"),
                $this->request->input("name"),
                $this->request->input("password"),
            );

            if ($form->hasValidationErrors()) {
                foreach ($form->getValidationErrors() as $error) {
                    $this->request->getSession()->setFlash("error", $error);
                }

                return new RedirectResponse("/sign-up");
            }

            $user = $form->save();

            $this->request->getSession()->setFlash("success", "Пользователь {$user->getEmail()} успешно зарегестрирован");

            $this->sessionAuthentication->login($user);

            return new RedirectResponse("/cabinet");
        }
    }