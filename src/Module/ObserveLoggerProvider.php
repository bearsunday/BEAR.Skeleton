<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\RepositoryModule\Annotation\CacheLog;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use Ray\Di\ProviderInterface;

/**
 * Aliases the unannotated logger to the cache log's instance: one request, one tree.
 *
 * A provider, not toInstance() - a compiled injector serializes bindings, and an instance
 * on a binding splits the tree per process (and fails outright if it holds a closure).
 *
 * @implements ProviderInterface<SemanticLoggerInterface>
 */
final class ObserveLoggerProvider implements ProviderInterface
{
    public function __construct(
        #[CacheLog]
        private readonly SemanticLoggerInterface $logger,
    ) {
    }

    public function get(): SemanticLoggerInterface
    {
        return $this->logger;
    }
}
