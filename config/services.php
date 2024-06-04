<?php

    use League\Container\Argument\Literal\ArrayArgument;
    use League\Container\Argument\Literal\StringArgument;
    use League\Container\Container;
    use League\Container\ReflectionContainer;
    use Yeager\Framework\Routing\IRouter;
    use Yeager\Framework\Routing\Router;
    use Yeager\Framework\HTTP\Kernel;
    use Symfony\Component\Dotenv\Dotenv;

    // Dot env
    $dotenv = new Dotenv();
    $dotenv->load(BASE_PATH . "/.env");

    // Application parameters
    $routes = require_once BASE_PATH . "/routes/web.php";

    // Application services
    $container = new Container();

    $container->delegate(new ReflectionContainer(true));

    $container->add("APP_ENV", new StringArgument($_ENV["APP_ENV"] ?? "development"));

    $container->add(IRouter::class, Router::class);

    $container->extend(IRouter::class)
        ->addMethodCall("registerRoutes", [new ArrayArgument($routes)]);

    $container->add(Kernel::class)
        ->addArgument(IRouter::class)
        ->addArgument($container);

    return $container;