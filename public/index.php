<?php

    define("BASE_PATH", dirname(__DIR__));
    define("WWW", "https://php-frameworkkz.lndo.site");

    require_once BASE_PATH . "/vendor/autoload.php";

    use League\Container\Container;
    use Yeager\Framework\HTTP\Request;
    use Yeager\Framework\HTTP\Kernel;

    $request = Request::createFromGlobals();

    /** @var Container $container * */
    $container = require_once BASE_PATH . "/config/services.php";

    $kernel = $container->get(Kernel::class);

    $response = $kernel->handle($request);
    $response->send();
