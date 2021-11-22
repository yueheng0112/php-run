<?php

namespace core;

class Container
{
    public static $instance;

    public $instances = [];

    public function setInstance($name, $object)
    {
        $this->instances[$name] = $object;
    }

    public function getInstance($name)
    {
        return $this->instances[$name] ?? null;
    }
}