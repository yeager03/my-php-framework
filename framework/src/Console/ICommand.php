<?php

    namespace Yeager\Framework\Console;

    interface ICommand
    {
        public function execute(array $parameters = []): int;
    }