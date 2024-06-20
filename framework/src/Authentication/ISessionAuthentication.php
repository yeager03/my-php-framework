<?php

    namespace Yeager\Framework\Authentication;

    interface ISessionAuthentication
    {
        public function authenticate(string $email, string $password): bool;

        public function login(IAuthUser $user): void;

        public function logout(): void;

        public function getUser(): IAuthUser;

        public function check(): bool;
    }