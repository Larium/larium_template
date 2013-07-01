<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\View;

class DefaultHelper extends Helper
{
    public function _init_escape()
    {

        $decode = function($string, $encoding='UTF-8') {
            return html_entity_decode($string, ENT_QUOTES, $encoding);
        };

        $this->getView()->assign('raw', $decode);
    } 
}
