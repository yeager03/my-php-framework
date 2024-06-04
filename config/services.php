<?php

    use League\Container\Argument\Literal\ArrayArgument;
    use League\Container\Argument\Literal\StringArgument;
    use League\Container\Container;
    use League\Container\ReflectionContainer;
    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\Routing\IRouter;
    use Yeager\Framework\Routing\Router;
    use Yeager\Framework\HTTP\Kernel;
    use Symfony\Component\Dotenv\Dotenv;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;

    // Dot env
    $dotenv = new Dotenv();
    $dotenv->load(BASE_PATH . "/.env");

    // Application parameters
    $routes = require_once BASE_PATH . "/routes/web.php";
    $APP_ENV = $_ENV["APP_ENV"] ?? "development";
    $VIEWS_PATH = BASE_PATH . "/views";

    // Application services
    $container = new Container();

    $container->delegate(new ReflectionContainer(true));

    $container->add("APP_ENV", new StringArgument($APP_ENV));

    $container->add(IRouter::class, Router::class);

    $container->extend(IRouter::class)
        ->addMethodCall("registerRoutes", [new ArrayArgument($routes)]);

    $container->add(Kernel::class)
        ->addArgument(IRouter::class)
        ->addArgument($container);

    $container->addShared("twig-loader", FilesystemLoader::class)
        ->addArgument(new StringArgument($VIEWS_PATH));

    $container->addShared("twig", Environment::class)
        ->addArgument("twig-loader");

    $container->inflector(BaseController::class)
        ->invokeMethod("setContainer", [$container]);

    return $container;