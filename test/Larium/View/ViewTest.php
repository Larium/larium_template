<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\View;

class ViewTest extends \PHPUnit_Framework_TestCase
{

    protected $path;
    
    public function setUp()
    {
        $this->path = __DIR__ . '/../../views';
    }
    
    public function testVariableAssign()
    {
        $view = new View($this->path);

        $view->assign('header', 'Hello World');

        $output = $view->render('index');

        $this->assertEquals('<h1>Hello World</h1>'.PHP_EOL, $output);
    }

    public function testBlock()
    {
        $view = new View($this->path);
        $view->assign('header', 'Hello World');
        
        $view->render('block');

        $callback = $view->getBlock('header');
        
        $this->expectOutputString('<div class="header">Hello World</div>'.PHP_EOL);

        $callback->__invoke($view);
    }
    
    public function testTemplateExtend()
    {
        $view = new View($this->path);
        $view->assign('header', 'Hello World');

        $output = <<<EOT
<!DOCTYPE html>
<html>
    <head>

    </head>
    
    <body>
        <div class="header">Hello World</div>
    </body>
</html>
EOT;
        $this->expectOutputString($output.PHP_EOL);
        
        echo $view->render('extended_block');
    }

    public function testDeepTemplateExtend()
    {

        $view = new View($this->path);
        $view->assign('header', 'Hello World');
        $output = <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <title>New Title</title>
    </head>
    
    <body>
        <div class="header_2">Hello World</div>
        <div class="content">
            <div class="body">Hello World</div>
        </div>
    </body>
</html>
EOT;
        $this->expectOutputString($output.PHP_EOL);
        
        echo $view->render('multi_extend');
    }

    public function testEscapeVariableAssign()
    {
        $view = new View($this->path);

        $view->assign('header', '<span>Hello World</span>');

        $output = $view->render('raw');

        $this->assertEquals('<h1><span>Hello World</span></h1>'.PHP_EOL, $output);
        
        $output = $view->render('escape');
        
        $this->assertNotEquals('<h1><span>Hello World</span></h1>'.PHP_EOL, $output);
        
        $this->assertEquals('<h1>&lt;span&gt;Hello World&lt;/span&gt;</h1>'.PHP_EOL, $output);
    }
}
