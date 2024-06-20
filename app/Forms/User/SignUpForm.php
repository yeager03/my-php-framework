<?php

    namespace App\Forms\User;

    use App\Entities\User;
    use App\Services\UserService;

    class SignUpForm
    {
        private string $email;
        private string $name;
        private string $password;
        private UserService $userService;


        public function __construct(UserService $userService)
        {
            $this->userService = $userService;
        }


        public function setFields(string $email, string $name, string $password): void
        {
            $this->email = $email;
            $this->name = $name;
            $this->password = $password;
        }

        public function save(): User
        {
            $user = User::create($this->email, $this->name, password_hash($this->password, PASSWORD_DEFAULT));

            return $this->userService->save($user);
        }

        public function getValidationErrors(): array
        {
            $errors = [];

            if (empty($this->email) || empty($this->name) || empty($this->password)) {
                $errors[] = "Заполните все поля";
            }

            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Некорректный email";
            }

            if (strlen($this->name) > 50) {
                $errors[] = "Максимальная длина имени 50 символов";
            }

            if (strlen($this->password) < 8) {
                $errors[] = "Минимальная длина пароля 8 символов";
            }

            if($this->userService->findByEmail($this->email)) {
                $errors[] = "Пользователь с таким email уже существует";
            }

            return $errors;
        }

        public function hasValidationErrors(): bool
        {
            return !empty($this->getValidationErrors());
        }


    }