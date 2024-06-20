<?php

    namespace Yeager\Framework\Authentication;

    interface IUserService
    {
        public function findByEmail(string $email): IAuthUser|null;
    }