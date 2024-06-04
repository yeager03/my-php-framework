<?php

    namespace Yeager\Framework\Console;

    use League\Container\Container;

    class Application
    {
        private Container $container;

        public function __construct(Container $container)
        {
            $this->container = $container;
        }

        public function run(): int
        {
            $argv = $_SERVER["argv"];
            $command_name = $argv[1] ?? null;

            if(is_null($command_name)) {
                throw new ConsoleException("Invalid console command");
            }

            /** @var ICommand $command */
            $command = $this->container->get("console:{$command_name}");

            $args = array_slice($argv, 2);

            $options = $this->parseOptions($args);

            return $command->execute($options);
        }

        private function parseOptions(array $args): array
        {
            $options = [];

            foreach ($args as $arg) {
                if(str_starts_with($arg, "--")) {
                    $option = explode("=", substr($arg, 2));

                    $options[$option[0]] = $option[1] ?? true;
                }
            }

            return $options;
        }
    }