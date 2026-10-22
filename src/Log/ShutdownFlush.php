<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Log;

use Koriym\SemanticLogger\SemanticLoggerInterface;
use Throwable;

use function error_log;
use function register_shutdown_function;

/**
 * Flushes the dev-context logger once, at process shutdown
 *
 * Nothing in the request path (`Bootstrap`, `bin/observe.php`) calls `flush()` itself, so this
 * is the only place a dev-context log gets written. Shutdown runs after the response is sent,
 * which is what makes it the request's end for a process that serves one request. `arm()` is
 * idempotent - bound `Scope::SINGLETON`, it is called once per process, but a second call is
 * harmless.
 *
 * Trimmed from BEAR.QueryRepository's `ShutdownFlush` for the skeleton's single-session,
 * single-process case: no `LogSinkInterface`, no concurrency detection.
 */
final class ShutdownFlush
{
    private bool $armed = false;

    public function __construct(
        private LogFileWriter $writer,
    ) {
    }

    public function arm(SemanticLoggerInterface $logger): void
    {
        if ($this->armed) {
            return;
        }

        $this->armed = true;
        register_shutdown_function(fn () => $this->flush($logger));
    }

    public function flush(SemanticLoggerInterface $logger): void
    {
        try {
            $this->writer->write($logger->flush());
        } catch (Throwable $e) {
            // The log is a side channel: a destination that fails must not change how the
            // request ended. Thrown from a shutdown callback this would surface as an uncaught
            // fatal.
            error_log('BEAR.Skeleton dev log: flush failed - ' . $e::class . ': ' . $e->getMessage());
        }
    }
}
