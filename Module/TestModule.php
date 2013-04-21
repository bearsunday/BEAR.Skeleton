<?php
/**
 * @package    Skeleton
 * @subpackage Module
 */
namespace Skeleton\Module;

use Skeleton\Module\ProdModule;
use BEAR\Package\Module as PackageModule;

/**
 * Test module
 *
 * @package    Skeleton
 * @subpackage Module
 */
class TestModule extends ProdModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new App\AppModule('test'));
        $this->install(new PackageModule\Resource\NullCacheModule($this));
    }
}
