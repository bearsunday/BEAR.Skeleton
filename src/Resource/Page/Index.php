<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Resource\Page;

use BEAR\Resource\Annotation\Link;
use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    /** @var array{greeting: string} */
    public $body;

    #[Link(rel: 'name:foo', href: '/index?name=Foo')]
    public function onGet(string $name = 'BEAR.Sunday'): static
    {
        $this->body = [
            'greeting' => 'Hello ' . $name,
        ];

        return $this;
    }
}
