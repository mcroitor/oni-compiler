<?php

namespace mc;

use \mc\logger;

class Test {
    private $name;
    private $callable;

    public function __construct(string $name, callable $callable = null)
    {
        $this->name = $name;
        $this->callable = $callable;
    }

    public static function create(string $name, callable $callable = null) {
        return new Test($name, $callable);
    }

    public function setCallable(callable $callable){
        $this->callable = $callable;
        return self::create($this->name, $callable);
    }

    public function run(){
        \mc\logger::stdout()->info("execute test " . $this->name);
        if(is_callable($this->callable)){
            ($this->callable)();
        }
        else{
            \mc\logger::stdout()->error("test is not defined");
        }
        \mc\logger::stdout()->info("execution is done");
    }
}