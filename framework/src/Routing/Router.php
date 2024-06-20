<?php

    namespace Yeager\Framework\Routing;

    use League\Container\Container;
    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\Request;

    class Router implements IRouter
    {
        public function dispatch(Request $request, Container $container): array
        {
            $handler = $request->getRouteHandler();
            $variables = $request->getRouteArguments();

            if (is_array($handler)) {
                [$controllerId, $method] = $handler;
                $controller = $container->get($controllerId);

                if (is_subclass_of($controller, BaseController::class)) {
                    $controller->setRequest($request);
                }

                $handler = [$controller, $method];
            }

            return [$handler, $variables];
        }
    }