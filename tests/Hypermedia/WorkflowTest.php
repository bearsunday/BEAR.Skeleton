<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Hypermedia;

use BEAR\Dev\Http\AbstractWorkflowTest;
use BEAR\Resource\ResourceInterface;
use BEAR\Resource\ResourceObject;
use BEAR\Skeleton\Injector;

class WorkflowTest extends AbstractWorkflowTest
{
    protected function newResource(): ResourceInterface
    {
        $injector = Injector::getInstance('app');

        return $injector->getInstance(ResourceInterface::class);
    }

    public function testIndex(): ResourceObject
    {
        $index = $this->resource->get('/index');
        $this->assertSame(200, $index->code);

        return $index;
    }

    /** @depends testIndex */
    public function testRelFoo(ResourceObject $response): ResourceObject
    {
        return $this->follow($response, 'name:foo');
    }
}
