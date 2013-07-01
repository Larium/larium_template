<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\View;

interface LayoutInterface
{
    public function setPath($path);

    public function getPath();
    
    public function setLayout($layout);

    public function setView(ViewInterface $view);

    public function render();

}
