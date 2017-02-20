<?php

namespace vendor;


use vendor\psr7\HttpRequest;
use vendor\psr7\HttpResponse;
use vendor\psr7\HttpStream;

class Container
{
    private $components;
    private $classes;

    public function __construct(array $config = [])
    {
        $this->components = new \stdClass();
        $this->setDefaultClasses();
    }

    public function __get($className)
    {
        echo '<pre>';

        if (isset($this->components->{$className})) {
            return $this->components->{$className};
        }
        echo $this->classes->{$className};
        if(!class_exists($this->classes->{$className})) {
            throw new \Exception ("Class $className is not exist");
        }

        if(method_exists($this->classes->{$className}, '__construct')) {
            $refMethod = new \ReflectionMethod($this->classes->{$className}, '__construct');
            $params = $refMethod->getParameters();

            $re_args = [];
            var_dump($params);
            foreach ($params as $key => $param) {
                if($param->isDefaultValueAvailable()) {
                    $re_args[$param->name] = $param->getDefaultValue();
                } else {
                    $class = $param->getClass();
                    if ($class !== null) {
                        $re_args[$param->name] = $this->{$class->name};
                    } else {
                        throw new \Exception($class->name . 'not found in container');
                    }
                }
            }
        }
    }

    private function setDefaultClasses(array $config = [])
    {
        $this->classes = (object)array_merge($this->getDefaultClasses(), $config);

    }

    private function getDefaultClasses()
    {
        return [
            'HttpRequest' => HttpRequest::class,

            'HttpResponse' => HttpResponse::class,
            'HttpStream' => HttpStream::class,
        ];
    }
}