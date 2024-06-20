<?php

    namespace Yeager\Framework\Authentication;

    interface IAuthUser
    {
        public function getId(): int|null;

        public function getEmail(): string;

        public function getPassword(): string;
    }