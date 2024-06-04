<?php

    namespace App\Controllers;

    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\Response;

    class HomeController extends BaseController
    {
        public function index(): Response
        {
            $name = "Alex";
            $title = "Home page";

            return $this->render("home.html.twig", compact("name", "title"));
        }
    }