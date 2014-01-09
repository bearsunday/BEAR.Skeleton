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
        $this->module = new AppModule('prod');
        parent::setUp();
    }

    public function testNew()
    {
        $this->assertInstanceOf('BEAR\Skeleton\Module\AppModule', $this->module);
    }
}
