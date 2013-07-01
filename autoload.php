<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

require_once 'ClassMap.php';

$classes = array(
    'Larium\\View\\Helper' => 'Larium/View/Helper.php',
    'Larium\\View\\DefaultHelper' => 'Larium/View/DefaultHelper.php',
    'Larium\\View\\ViewNotFoundException' => 'Larium/View/ViewNotFoundException.php',
    'Larium\\View\\View' => 'Larium/View/View.php',
    'Larium\\View\\ViewInterface' => 'Larium/View/ViewInterface.php',
    'Larium\\View\\LayoutInterface' => 'Larium/View/LayoutInterface.php',
    'Larium\\View\\Layout' => 'Larium/View/Layout.php',
);

ClassMap::load(__DIR__ . "/src/", $classes)->register();
