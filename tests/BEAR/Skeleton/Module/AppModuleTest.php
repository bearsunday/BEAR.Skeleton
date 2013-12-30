<?php

namespace BEAR\Skeleton\Module;

class AppModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AppModule
     */
    private $module;

    protected function setUp()
    {
        parent::setUp();
        $this->module = new AppModule('prod');
    }

    public function testNew()
    {
        $this->assertInstanceOf('BEAR\Skeleton\Module\AppModule', $this->module);
        $a = $this->module['BEAR\Sunday\Extension\Application\AppInterface'];
        echo $a;

    }
}
