<?php
/**
 * @package    Skeleton
 * @subpackage Module
 */
namespace Skeleton\Module;

use BEAR\Sunday\Module as SundayModule;
use BEAR\Package\Provide as PackageModule;

/**
 * API module
 *
 * @package    Skeleton
 * @subpackage Module
 */
class ApiModule extends ProdModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new ProdModule);

        // view module
        $this->install(new PackageModule\ResourceView\HalModule($this));
        //$this->install(new SundayModule\Resource\JsonModule($this));
    }
}
