<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\AppMeta\AbstractAppMeta;
use FilesystemIterator;
use Ray\Di\ProviderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use function file_get_contents;
use function file_put_contents;
use function is_file;
use function max;

/**
 * A file pool under var/tmp, so a cached resource is still there on the next request
 *
 * Entries live as long as their #[Cacheable] expiry, so the pool is emptied when a file
 * under src/ changes; otherwise an edited resource would keep serving its old body.
 *
 * @implements ProviderInterface<AdapterInterface>
 */
final class DevPoolProvider implements ProviderInterface
{
    public function __construct(
        private readonly AbstractAppMeta $appMeta,
    ) {
    }

    public function get(): AdapterInterface
    {
        $dir = $this->appMeta->tmpDir . '/query-repository';
        $pool = new FilesystemAdapter('', 0, $dir);
        $stampFile = $dir . '/source-mtime';
        $stamp = (string) $this->latestSourceMtime();
        if (! is_file($stampFile) || file_get_contents($stampFile) !== $stamp) {
            $pool->clear();
            file_put_contents($stampFile, $stamp);
        }

        return $pool;
    }

    private function latestSourceMtime(): int
    {
        $latest = 0;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->appMeta->appDir . '/src', FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
        );
        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $latest = max($latest, $file->getMTime());
        }

        return $latest;
    }
}
