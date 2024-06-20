<?php

    use App\Services\UserService;
    use League\Container\Argument\Literal\ArrayArgument;
    use League\Container\Argument\Literal\StringArgument;
    use League\Container\Container;
    use League\Container\ReflectionContainer;
    use Yeager\Framework\Authentication\ISessionAuthentication;
    use Yeager\Framework\Authentication\SessionAuthentication;
    use Yeager\Framework\Console\Application;
    use Yeager\Framework\Console\Commands\MigrateCommand;
    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\Dbal\ConnectionFactory;
    use Yeager\Framework\HTTP\Middleware\ExtractRouteInfo;
    use Yeager\Framework\HTTP\Middleware\IRequestHandler;
    use Yeager\Framework\HTTP\Middleware\RequestHandler;
    use Yeager\Framework\HTTP\Middleware\RouterDispatch;
    use Yeager\Framework\Routing\IRouter;
    use Yeager\Framework\Routing\Router;
    use Yeager\Framework\HTTP\Kernel;
    use Yeager\Framework\Console\Kernel as ConsoleKernel;
    use Symfony\Component\Dotenv\Dotenv;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use Doctrine\DBAL\Connection;
    use Yeager\Framework\Session\ISession;
    use Yeager\Framework\Session\Session;
    use Yeager\Framework\Template\TwigFactory;

    // Dot env
    $dotenv = new Dotenv();
    $dotenv->load(BASE_PATH . "/.env");

    // Application parameters
    $routes = require_once BASE_PATH . "/routes/web.php";
    $APP_ENV = $_ENV["APP_ENV"] ?? "development";
    $VIEWS_PATH = BASE_PATH . "/views";
    $DATABASE_URL = "pdo-mysql://lamp:lamp@database:3306/lamp?charset=utf8mb4";

    // Application services
    $container = new Container();

    $container->delegate(new ReflectionContainer(true));

    $container->add("framework-commands-namespace", new StringArgument("Yeager\\Framework\\Console\\Commands\\"));

    $container->add("APP_ENV", new StringArgument($APP_ENV));

    $container->add(IRouter::class, Router::class);

    $container->add(IRequestHandler::class, RequestHandler::class)
        ->addArgument($container);

    $container->add(Kernel::class)
        ->addArguments([
            IRouter::class,
            $container,
            IRequestHandler::class
        ]);

    $container->addShared(ISession::class, Session::class);

    $container->add("twig-factory", TwigFactory::class)
        ->addArguments([
            new StringArgument($VIEWS_PATH),
            ISession::class,
            ISessionAuthentication::class
        ]);

    $container->addShared("twig", function () use ($container) {
        return $container->get("twig-factory")->create();
    });

    $container->inflector(BaseController::class)
        ->invokeMethod("setContainer", [$container]);

    $container->add(ConnectionFactory::class)
        ->addArgument(new StringArgument($DATABASE_URL));

    $container->addShared(Connection::class, function () use ($container): Connection {
        return $container->get(ConnectionFactory::class)->create();
    });

    $container->add(Application::class)
        ->addArgument($container);

    $container->add(ConsoleKernel::class)
        ->addArgument($container)
        ->addArgument(Application::class);

    $container->add("console:migrate", MigrateCommand::class)
        ->addArgument(Connection::class)
        ->addArgument(new StringArgument(BASE_PATH . "/database/migrations"));

    $container->add(RouterDispatch::class)
        ->addArguments([
            IRouter::class,
            $container
        ]);

    $container->add(ISessionAuthentication::class, SessionAuthentication::class)
        ->addArguments([
            UserService::class,
            ISession::class
        ]);

    $container->add(ExtractRouteInfo::class)
        ->addArgument(new ArrayArgument($routes));

    return $container;