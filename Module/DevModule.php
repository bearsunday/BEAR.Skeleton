<?php
/**
 * @package    Skeleton
 * @subpackage Module
 */
namespace Skeleton\Module;

use BEAR\Package\Module as PackageModule;
use Ray\Di\AbstractModule;

/**
 * Dev module
 *
 * @package    Skeleton
 * @subpackage Module
 */
class DevModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new App\AppModule('dev'));
        $this->install(new PackageModule\Resource\DevResourceModule($this));
    }
}
