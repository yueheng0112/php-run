<?php

namespace core;

/**
 * @property Di $di
 * Class App
 * @package core
 */
class App extends Container
{
    /** @var Request */
    public $request;
    /** @var array */
    public $bind = [
        'di'      => Di::class,
        'request' => Request::class,
    ];

    public function run()
    {
        static::$instance = $this;

        $this->bind();

        $this->dispatch();
    }

    public function dispatch()
    {
        return (new Dispatch($this))->run();
    }

    private function bind()
    {
        foreach ($this->bind as $name => $class) {
            if (!$this->getInstance($name)) {
                $object = $this->di->make($class);
                $this->setInstance($name, $object);
                $this->$name = $object;
            }
        }
    }

    public function __set($property, $object)
    {
        $this->$property = $object;
    }

    public function __get($property)
    {
        if (isset($this->bind[$property])) {
            $object          = new $this->bind[$property];
            $this->$property = $object;
        }
        return $this->$property;
    }

}