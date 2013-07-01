<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\View;

class Layout implements LayoutInterface
{
    protected $path;

    protected $layout;

    protected $view;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function setView(ViewInterface $view)
    {
        $this->view = $view;
    }

    public function render()
    {
        $_content_ = $this->view->render();
        $this->view->setPath($this->getPath());

        return $this->view->render(
            $this->layout, 
            array(
                '_content_' => $_content_
            )
        );
    }
}
