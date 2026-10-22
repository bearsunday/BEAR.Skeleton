<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Log;

use Koriym\SemanticLogger\LogJson;

use function array_slice;
use function bin2hex;
use function chmod;
use function count;
use function error_log;
use function file_put_contents;
use function glob;
use function gmdate;
use function is_dir;
use function json_encode;
use function microtime;
use function mkdir;
use function random_bytes;
use function rename;
use function rsort;
use function sprintf;
use function unlink;

use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

/**
 * One file per request, plus a fixed path for the latest one
 *
 * `stree` takes a single log file, so a request is written whole rather than appended as a
 * line: `vendor/bin/stree var/log/<context>/latest.json` renders the request that just ran, and
 * the timestamped siblings keep the ones before it. Names are UTC so they sort chronologically,
 * and `latest.json` is renamed into place so a reader never opens a half-written file.
 *
 * A destination that cannot be written is reported to `error_log()` and never thrown: this runs
 * at shutdown, where an exception is an uncaught fatal.
 *
 * Trimmed from BEAR.QueryRepository's `LogFileWriter` for the skeleton's single-session,
 * single-process case: no `LogWriterInterface`, no concurrency policy.
 */
final class LogFileWriter
{
    public const LATEST = 'latest.json';
    private const REQUEST_GLOB = '[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-*.json';

    /** @param int $keep number of timestamped files to retain (the oldest are removed) */
    public function __construct(
        private string $dir,
        private int $keep = 100,
    ) {
    }

    public function write(LogJson $log): void
    {
        if ($log->records === []) {
            return; // nothing was recorded: an empty file would claim the request did nothing
        }

        $json = json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false || ! $this->prepareDir()) {
            return;
        }

        $now = microtime(true);
        $seconds = (int) $now;
        $micro = (int) (($now - (float) $seconds) * 1000000.0);
        $name = sprintf('%s-%06d.json', gmdate('Ymd-His', $seconds), $micro);
        if (! $this->put($this->dir . '/' . $name, $json)) {
            return;
        }

        $this->putLatest($json);
        $this->prune();
    }

    /** @SuppressWarnings("PHPMD.ErrorControlOperator") */
    private function prepareDir(): bool
    {
        // The second is_dir() is the concurrent case: a sibling process won the mkdir
        if (is_dir($this->dir) || @mkdir($this->dir, 0700, true) || is_dir($this->dir)) {
            return true;
        }

        error_log('BEAR.Skeleton dev log: cannot create the log directory ' . $this->dir);

        return false;
    }

    /** @SuppressWarnings("PHPMD.ErrorControlOperator") */
    private function put(string $path, string $json): bool
    {
        if (@file_put_contents($path, $json) === false) {
            error_log('BEAR.Skeleton dev log: cannot write ' . $path);

            return false;
        }

        @chmod($path, 0600);

        return true;
    }

    /**
     * Renamed into place so a concurrent reader sees either the old request or the new one
     *
     * @SuppressWarnings("PHPMD.ErrorControlOperator")
     */
    private function putLatest(string $json): void
    {
        $latest = $this->dir . '/' . self::LATEST;
        $pending = $latest . '.' . bin2hex(random_bytes(4)) . '.tmp';
        if ($this->put($pending, $json) && @rename($pending, $latest)) {
            return;
        }

        @unlink($pending); // a copy that could not be written or moved is not left behind
    }

    /** @SuppressWarnings("PHPMD.ErrorControlOperator") */
    private function prune(): void
    {
        $files = glob($this->dir . '/' . self::REQUEST_GLOB);
        if ($files === false || count($files) <= $this->keep) {
            return;
        }

        rsort($files);
        foreach (array_slice($files, $this->keep) as $stale) {
            @unlink($stale); // a sibling process pruning the same list is not an error
        }
    }
}
