<?php

namespace core;

class Dispatch
{

    public function run(App $app)
    {
        $request = $app->request;
        $class   = "app\\{$request->module}\\{$request->controller}";
        $action  = $request->action;
        $data    = $this->invoke($class, $action, $request->param);
        return $data;
    }

    public function invoke($class, $action, $param)
    {
        $di       = new Di();
        $instance = $di->make($class, []);
        $data     = $di->invoke($instance, $action, $param);
        return $data;
    }

}