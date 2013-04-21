<?php
/**
 * @package    Skeleton
 * @subpackage Module
 */
namespace Skeleton\Module;

use Ray\Di\AbstractModule;

/**
 * Production module
 *
 * @package    Skeleton
 * @subpackage Module
 */
class ProdModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new App\AppModule('prod'));
    }
}
