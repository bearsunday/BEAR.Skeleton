<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\Skeleton\Log\ShutdownFlush;
use Koriym\SemanticLogger\SemanticLogger;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use Ray\Di\ProviderInterface;

/**
 * Builds the dev-context logger and arms its process-end flush
 *
 * @implements ProviderInterface<SemanticLoggerInterface>
 */
final class DevSemanticLoggerProvider implements ProviderInterface
{
    public function __construct(
        private ShutdownFlush $shutdownFlush,
    ) {
    }

    public function get(): SemanticLoggerInterface
    {
        $logger = new SemanticLogger();
        $this->shutdownFlush->arm($logger);

        return $logger;
    }
}
