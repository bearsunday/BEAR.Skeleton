<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Http;

use BEAR\Dev\Http\HttpResource;
use BEAR\Resource\ResourceInterface;
use BEAR\Skeleton\Hypermedia\WorkflowTest as Workflow;

class WorkflowTest extends Workflow
{
    protected function newResource(): ResourceInterface
    {
        return new HttpResource('127.0.0.1:8080', __DIR__ . '/index.php', __DIR__ . '/log/workflow.log');
    }
}
