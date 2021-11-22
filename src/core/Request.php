<?php

namespace core;

class Request
{
    public $command;
    public $param;
    public $controller;
    public $action;
    public $path;
    public $controllerPath;
    public $initiated = false;

    public function __construct()
    {
        if (!$this->initiated) {
            $this->parse();
        }
    }

    public function instance()
    {
        if (!$this->initiated) {
            $this->parse();
        }
        return $this;
    }

    private function parse()
    {
        $this->command = $argv = $_SERVER['argv'];
        array_shift($argv);
        $pathRaw = array_shift($argv);
        $pathArr = explode('/', trim($pathRaw, '/'));

        $this->action         = array_pop($pathArr) ?? 'index';
        $this->controller     = array_pop($pathArr) ?? 'index';
        $controllerPath       = empty($pathArr) ? '' : strtolower(implode('.', $pathArr));
        $controller           = ucfirst($this->controller);
        $this->controllerPath = empty($controllerPath) ? $controller : $controllerPath . '.' . $controller;
        $this->path           = "{$this->controllerPath}/{$this->action}";
        $this->param          = $this->parseParam($argv);
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