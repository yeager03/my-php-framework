<?php

    namespace Yeager\Framework\Console\Commands;

    use Yeager\Framework\Console\ICommand;

    class MigrateCommand implements ICommand
    {
        private string $command_name = "migrate";

        public function execute(array $parameters = []): int
        {
            dd($parameters);

            return 0;
        }
    }