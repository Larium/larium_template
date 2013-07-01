<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\View;

interface ViewInterface
{
    /**
     * Sets the absolute path of templates location.
     * 
     * @param string $path
     *
     * @access public
     * @return void
     */
    public function setPath($path);

    /**
     * Gets the absolute path of templates location.
     * 
     * @access public
     * @return string
     */
    public function getPath();

    /**
     * Assign a variable so it can be used inside the template file.
     * 
     * @param string $variable
     * @param mixed $value
     *
     * @access public
     * @return void
     */
    public function assign($variable, $value);

    /**
     * Gets an assigned varible. Returns null if variable not exists. 
     * 
     * @param string $variable
     *
     * @access public
     * @return mixed|null
     */
    public function get($variable);

    /**
     * Renders the given template file and optional assign given variables.
     *
     * Given variables will be merged with the variables assigned with
     * @see assign() method.
     * 
     * @param string $template  The filename only, without extension, of template 
     *                          file. Not absolute path required.
     * @param array  $variables An array with key/values variable to assign to 
     *                          template file.
     *
     * @access public
     * @return string
     */
    public function render($template, $variables = array());

    /**
     * Gets all assigned variables. 
     * 
     * @access public
     * @return array
     */
    public function getVariables();

    /**
     * Gets the template file extension associated with this View instance.
     * 
     * @access public
     * @return void
     */
    public function getExtension();

    /**
     * Clears all assigned variables.
     * 
     * @access public
     * @return void
     */
    public function clear();

    /**
     * Sets the template file to render without the extension.
     * No full path of template files needed.
     *
     * @param string $template The template file to render.
     *
     * @throws ViewNotFoundException
     * @access public
     * @return void
     */
    public function setTemplate($template);

    /**
     * Gets the absolute path of the template file to render.
     *
     * @return string 
     */
    public function getTemplate();
}
