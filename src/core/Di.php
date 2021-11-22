<?php

namespace core;

class Di
{
    public function make($class, $vars = [])
    {
        $reflect     = new \ReflectionClass($class);
        $constructor = $reflect->getConstructor();
        $args        = $constructor ? $this->bindParams($constructor, $vars) : [];
        $object      = $reflect->newInstanceArgs($args);
        return $object;
    }

    public function invoke($instance, $method, $param)
    {
        $reflect = new \ReflectionMethod($instance, $method);
        return $this->invokeReflectMethod($instance, $reflect, $param);
    }

    private function bindParams(\ReflectionFunctionAbstract $reflect, $vars = [])
    {
        $params = $reflect->getParameters();

        reset($vars);
        $type = key($vars) === 0 ? 'numberKey' : 'stringKey';

        $args = [];
        foreach ($params as $param) {
            $name  = $param->getName();
            $class = $param->getClass();

            if ($class) {
                $args[] = $this->getObjectParam($class->getName(), $vars);
            } else {
                if ('numberKey' == $type && !empty($vars)) {
                    $args[] = array_shift($vars);
                } elseif ('stringKey' == $type && isset($vars[$name])) {
                    $args[] = $vars[$name];
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    $args[] = null;
                }
            }
        }
        return $args;
    }

    private function getObjectParam(string $className, array &$vars)
    {
        $array = $vars;
        $value = array_shift($array);

        if ($value instanceof $className) {
            $result = $value;
            array_shift($vars);
        } else {
            $result = $this->make($className);
        }

        return $result;
    }


    private function invokeReflectMethod($instance, $reflect, array $vars = [])
    {
        $args = $this->bindParams($reflect, $vars);

        return $reflect->invokeArgs($instance, $args);
    }
}