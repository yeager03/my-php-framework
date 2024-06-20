<?php

    namespace App\Forms\User;


    class SignInForm
    {
        private string $email;
        private string $password;

        public function setFields(string $email, string $password): void
        {
            $this->email = $email;
            $this->password = $password;
        }

        public function getValidationErrors(): array
        {
            $errors = [];

            if (empty($this->email) || empty($this->password)) {
                $errors[] = "Заполните все поля";
            }

            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Введите корректный email";
            }

            return $errors;
        }

        public function hasValidationErrors(): bool
        {
            return !empty($this->getValidationErrors());
        }
    }