<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\View;

/**
 * View class
 *
 */
class View implements ViewInterface
{
    /**
     * @var string The extension of template files.
     */
    private $extension = '.phtml';

    /**
     * @var string The directory path to template files.
     */
    protected $path;

    /**
     * @var string The full path to the template file to render.
     */
    protected $template;

    /**
     * @var array Assigned variables for current template.
     */
    private $variables = array();

    /**
     * An array with \Closure instances.
     * The key is the name of \Closure.
     * 
     * @var array
     */
    private $blocks = array();

    private $extended;

    public function __construct($path=null)
    {
        $this->setPath($path);
        DefaultHelper::register($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function assign($variable, $value)
    {
        if (is_string($value)) {
            $value = htmlentities($value, ENT_QUOTES, 'UTF-8', false);
        }

        $this->variables[$variable] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate($template)
    {
        $this->template = $this->getPath() . $template . $this->getExtension();

        if (!file_exists($this->template)) {
            throw new ViewNotFoundException("$this->template file not exists");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->variables = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return $this->extension;
    }

    public function __set($variable, $value)
    {
        $this->assign($variable, $value);
    }

    public function __get($variable)
    {
        if (!$this->__isset($variable)) {
            throw new \InvalidArgumentException(sprintf("Undefined variable '%s'", $variable));
        }
        
        return $this->variables[$variable];
    }

    public function __isset($variable)
    {
        return array_key_exists($variable, $this->variables);
    }

    public function __call($name, $args)
    {
        if ($this->__isset($name) && $this->$name instanceof \Closure) {

            $function = new \ReflectionFunction($this->$name);

            return $function->invokeArgs($args);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($variable)
    {
        if (array_key_exists($variable, $this->variables)) {
            return $this->variables[$variable];
        }

        return null;
    }

    /**
     * Sets and/or renders a block.
     *
     * When $callback arguments is set then the callback is stored with 
     * the given $name
     *
     * When $callback ommited then executes the callback stored with the 
     * given name.
     * 
     * @param string $name 
     * @param Closure $callback 
     *
     * @access public
     * @return void|mixed
     */   
    public function block($name, \Closure $callback=null)
    {
        if (null === $callback) {
            if ($this->hasBlock($name)) {
                $callback = $this->blocks[$name];

                $callback($this);
            }
        } else {
            if (!$this->hasBlock($name)) {
                $this->blocks[$name] = $callback;
            }
        }
    }


    /**
     * Return a block as callback. 
     * 
     * @param string $name
     *
     * @access public
     * @return null|Closure
     */
    public function getBlock($name)
    {
        if ($this->hasBlock($name)) {
            return $this->blocks[$name];
        }

        return null;
    }

    /**
     * Checks if a blck with the given name exists.
     * 
     * @param string $name
     *
     * @access public
     * @return void
     */
    public function hasBlock($name)
    {
        return array_key_exists($name, $this->blocks);
    }

    /**
     * extend 
     * 
     * @param string $template
     *
     * @access public
     * @return void
     */
    public function extend($template)
    {
       $this->extended = $template; 
    }

    /**
     * {@inheritdoc}
     */
    public function render($template=null, $variables=array())
    {
        $this->extended = null;

        $content = $this->render_content($template, $variables);
         
        if ($this->extended) {
            $extended = $this->extended;//$this->getPath() . $this->extended . $this->getExtension(); 
            // if not empty content then should throw exception cause child 
            // templates should not render any content immediately.
            $content = trim($content);
            if (!empty($content)) {
                throw new \Exception(sprintf("Template '%s' extends another template so should not render any content.",realpath($template)));
            }
            
            return $this->render($extended, $variables);

        } else {

            return $content;
        }
    }

    private function render_content($template, array $variables=array())
    {
        $template ? $this->setTemplate($template) : null;

        foreach ($variables as $variable=>$value) {
            $this->assign($variable, $value);
        }

        $template = $this->getTemplate();

        extract($this->variables);
        $level = ob_get_level();
        ob_start();
        ob_implicit_flush(0);
        try {
            include $template;
        } catch (\Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }
        
        return ob_get_clean();       
    }
}
