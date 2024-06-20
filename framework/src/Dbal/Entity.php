<?php

    namespace Yeager\Framework\Dbal;

    abstract class Entity
    {
        abstract public function setId(?int $id): void;
    }