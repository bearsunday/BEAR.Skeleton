<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Resource;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\QueryRepository\Log\LogFileWriter;
use BEAR\QueryRepository\Log\LogSinkInterface;
use BEAR\Resource\ResourceInterface;
use BEAR\Skeleton\Injector;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use PHPUnit\Framework\TestCase;

use function file_exists;
use function file_get_contents;
use function json_decode;
use function unlink;

class DevModuleTest extends TestCase
{
    public function testGetRecordsExactlyOneRootResourceRequest(): void
    {
        $injector = Injector::getInstance('dev-app');
        $resource = $injector->getInstance(ResourceInterface::class);
        $resource->get('page://self/index', ['name' => 'BEAR.Sunday']);

        $log = $injector->getInstance(SemanticLoggerInterface::class)->flush()->toArray();

        $this->assertCount(1, $log['log']);
        $root = $log['log'][0];
        $this->assertSame('resource_request', $root['type']);
        $this->assertSame(200, $root['close']['context']['code']);
        // Nothing else recorded under the root: observation does not record itself.
        $this->assertArrayNotHasKey('log', $root);
    }

    /** Guards the module wiring that makes observation automatic: nothing in the request path calls flush() itself. */
    public function testFlushWritesLatestJsonForDevContextRequest(): void
    {
        $injector = Injector::getInstance('dev-app');
        $logDir = $injector->getInstance(AbstractAppMeta::class)->logDir . '/observe';
        $latest = $logDir . '/' . LogFileWriter::LATEST;
        if (file_exists($latest)) {
            unlink($latest); // a stale file from an earlier test run must not make this pass vacuously
        }

        $resource = $injector->getInstance(ResourceInterface::class);
        $resource->get('page://self/index', ['name' => 'BEAR.Sunday']);

        $logger = $injector->getInstance(SemanticLoggerInterface::class);
        // What the armed shutdown callback does; triggered here instead of waiting for the
        // process to actually end.
        $injector->getInstance(LogSinkInterface::class)->flush($logger);

        $this->assertFileExists($latest);
        $written = json_decode((string) file_get_contents($latest), true);
        $this->assertSame('resource_request', $written['log'][0]['type']);
        $this->assertSame(200, $written['log'][0]['close']['context']['code']);
    }
}
