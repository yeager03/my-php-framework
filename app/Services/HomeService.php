<?php

    namespace App\Services;

    use Yeager\Framework\Dbal\EntityService;

    class HomeService
    {
        private EntityService $entityService;

        public function __construct(EntityService $entityService)
        {
            $this->entityService = $entityService;
        }
    }