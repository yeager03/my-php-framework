<?php

    namespace Yeager\Framework\Controller;

    use Psr\Container\ContainerInterface;
    use Psr\Container\ContainerExceptionInterface;
    use Psr\Container\NotFoundExceptionInterface;
    use Twig\Environment;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;
    use Yeager\Framework\HTTP\Response;

    abstract class BaseController
    {
        protected ContainerInterface|null $container = null;

        public function setContainer(ContainerInterface $container): void
        {
            $this->container = $container;
        }

        /**
         * @throws NotFoundExceptionInterface
         * @throws SyntaxError
         * @throws ContainerExceptionInterface
         * @throws RuntimeError
         * @throws LoaderError
         */
        public function render(string $view, array $params = [], Response|null $response = null): Response
        {
            /** @var Environment $twig */
            $twig = $this->container->get("twig");

            if (is_null($response)) {
                $response = new Response();
            }

            $content = $twig->render($view, $params);

            $response->setContent($content);

            return $response;
        }
    }