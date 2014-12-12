<?php

namespace BEAR\Skeleton\Module;

use BEAR\Sunday\Extension\Application\AbstractApp;
use BEAR\Sunday\Extension\Application\AppInterface;
use Ray\Di\Injector;

class AppModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        $injector = new Injector(new AppModule, $_ENV['TMP_DIR']);
        $app = $injector->getInstance(AppInterface::class);
        $this->assertInstanceOf(AbstractApp::class, $app);
    }
}
