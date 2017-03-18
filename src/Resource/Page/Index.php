<?php
namespace BEAR\Skeleton\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet(string $name = 'BEAR.Sunday') : ResourceObject
    {
        $this['greeting'] = 'Hello ' . $name;

        return $this;
    }
}
