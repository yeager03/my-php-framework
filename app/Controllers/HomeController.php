<?php

    namespace App\Controllers;

    use Yeager\Framework\HTTP\Response;

    class HomeController
    {
        public function index(): Response
        {
            $content = "<h1>Home Page!</h1>";

            return new Response($content);
        }
    }