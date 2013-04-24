<?php
namespace Skeleton\Module;

use Skeleton\Module\ProdModule;
use BEAR\Package\Module as PackageModule;

/**
 * Test module
 *
 */
class TestModule extends ProdModule
{
    protected function configure()
    {
        $this->install(new App\AppModule('test'));
        $this->install(new PackageModule\Resource\NullCacheModule($this));
    }
}
