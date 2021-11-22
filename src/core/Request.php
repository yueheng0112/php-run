<?php

namespace core;

class Request
{
    public $command;
    public $param;
    public $module;
    public $controller;
    public $action;
    public $path;

    public function __construct()
    {
        $this->command = $argv = $_SERVER['argv'];
        array_shift($argv);
        $pathRaw = array_shift($argv);
        list($module, $controller, $action) = explode('/', $pathRaw);
        $this->module     = strtolower($module);
        $this->controller = ucfirst($controller);
        $this->action     = ucfirst($action);
        $this->path       = "{$this->module}/{$this->controller}/{$this->action}";
        $this->param      = $this->parseParam($argv);
    }

    private function parseParam($param)
    {
        $data = [];
        array_map(function ($val) use (&$data) {
            list($k, $v) = explode('=', $val);
            $data[$k] = $v;
        }, $param);
        return $data;
    }
}