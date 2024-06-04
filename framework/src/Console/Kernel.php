<?php

    namespace Yeager\Framework\Console;

    use League\Container\Container;
    use DirectoryIterator;
    use ReflectionClass;
    use ReflectionException;

    class Kernel
    {
        private Container $container;
        private Application $application;

        public function __construct(Container $container, Application $application)
        {
            $this->container = $container;
            $this->application = $application;
        }

        public function handle(): int
        {
            $this->registerCommands();

            $status = $this->application->run();

            dd($status);

            return 0;
        }

        /**
         * @throws ReflectionException
         */
        public function registerCommands(): void
        {
            $commandFiles = new DirectoryIterator(__DIR__ . "/Commands");
            $namespace = $this->container->get("framework-commands-namespace");


            /** @var DirectoryIterator $commandFile */
            foreach ($commandFiles as $commandFile) {
                if (!$commandFile->isFile()) {
                    continue;
                }

                $command = $namespace . pathinfo($commandFile, PATHINFO_FILENAME);

                if (is_subclass_of($command, ICommand::class)) {
                    $command_name = (new ReflectionClass($command))
                        ->getProperty("command_name")
                        ->getDefaultValue();

                    $this->container->add("console:{$command_name}", $command);
                }
            }
        }
    }