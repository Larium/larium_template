<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\View;

abstract class Helper
{
    private $view;

    final function __construct(ViewInterface $view) {

        $this->view = $view;
        
        $this->init();
    }

    final public function init()
    {
        $ref = new \ReflectionClass($this);
        $methods = $ref->getMethods();
        foreach ($methods as $method) {
            if (0 === strpos($method->name, '_init_')) {
                $method->invoke($this);
            }
        }
    }

    public function getView()
    {
        return $this->view;
    }

    public static function register(ViewInterface $view)
    {
        return new static($view);
    }
}
