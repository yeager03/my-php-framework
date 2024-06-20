<?php

    namespace Yeager\Framework\Providers;

    interface IServiceProvider
    {
        public function register(): void;
    }