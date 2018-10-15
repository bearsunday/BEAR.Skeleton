<?php
namespace BEAR\Skeleton\Module;

use BEAR\Sunday\Extension\Application\AbstractApp;
use BEAR\Sunday\Extension\Transfer\HttpCacheInterface;
use Ray\Di\Di\Inject;

class App extends AbstractApp
{
    /**
     * @var HttpCacheInterface
     */
    public $httpCache;

    /**
     * @Inject
     */
    public function setHttpCache(HttpCacheInterface $httpCache)
    {
        $this->httpCache = $httpCache;
    }
}
