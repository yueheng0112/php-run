<?php

namespace core;

class Dispatch
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        $request = $this->app->request;
        $cPath   = str_replace('.', "\\", $request->controllerPath);
        $class   = "app\\$cPath";
        $data    = $this->invoke($class, $request->action, $request->param);
        return $data;
    }

    public function invoke($class, $action, $param)
    {
        $instance = $this->make($class);
        $data     = $this->app->di->invoke($instance, $action, $param);
        return $data;
    }

    private function make($class)
    {
        $name = $this->app->request->controller;
        if (isset($this->app->bind[$name])) {
            return $this->app->bind[$name];
        }
        return $this->app->di->make($class, []);
    }

}