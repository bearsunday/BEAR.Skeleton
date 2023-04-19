<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Resource\Page;

use BEAR\Resource\ResourceInterface;
use BEAR\Skeleton\Injector;
use BEAR\Skeleton\Resource\Page\Index;
use PHPUnit\Framework\TestCase;

use function assert;

class IndexTest extends TestCase
{
    /** @var ResourceInterface */
    private $resource;

    protected function setUp(): void
    {
        $injector = Injector::getInstance('app');
        $this->resource = $injector->getInstance(ResourceInterface::class);
    }

    public function testOnGet(): void
    {
        $ro = $this->resource->get('page://self/index', ['name' => 'BEAR.Sunday']);
        assert($ro instanceof Index);
        $this->assertSame(200, $ro->code);
        $this->assertSame('Hello BEAR.Sunday', $ro->body['greeting']);
    }
}
