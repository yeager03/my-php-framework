<?php

    namespace App\Controllers;

    use Yeager\Framework\Controller\BaseController;
    use Yeager\Framework\HTTP\Response;

    class CabinetController extends BaseController
    {
        public function index(): Response
        {
            $title = "Cabinet page";

            return $this->render("cabinet.html.twig", compact("title"));
        }
    }