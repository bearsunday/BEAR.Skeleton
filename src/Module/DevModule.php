<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\EventSourcing\Filtered;
use BEAR\EventSourcing\Module\EventSourcingModule;
use BEAR\EventSourcing\Recorded;
use BEAR\EventSourcing\RecordedMethods;
use BEAR\EventSourcing\Resource\BodyStoreInterface;
use BEAR\EventSourcing\Resource\FileBodyStore;
use BEAR\EventSourcing\Resource\SemanticLogInvoker;
use BEAR\Package\AbstractAppModule;
use BEAR\Resource\InvokerInterface;
use BEAR\Skeleton\Log\LogFileWriter;
use BEAR\Skeleton\Log\ShutdownFlush;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use Ray\Di\Scope;

/**
 * Resolved for a `dev-` prefixed context; absent from `composer install --no-dev`.
 *
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
final class DevModule extends AbstractAppModule
{
    private const ORIGINAL_INVOKER = 'original_invoker';

    /** @SuppressWarnings("PHPMD.StaticAccess") */
    protected function configure(): void
    {
        $bodyDir = $this->appMeta->logDir . '/es-bodies';
        FileBodyStore::clearDirectory($bodyDir);

        // A context module inherits the whole inner chain, so it renames the
        // chain's own InvokerInterface and decorates it in place. Re-providing
        // a wrapped PackageModule here would register the package pointcuts a
        // second time and every interceptor would run twice.
        $this->rename(InvokerInterface::class, self::ORIGINAL_INVOKER);
        $this->bind(InvokerInterface::class)
            ->toConstructor(SemanticLogInvoker::class, [
                'invoker' => self::ORIGINAL_INVOKER,
                'recordedMethods' => Recorded::class,
                'paramsFilter' => Filtered::class,
            ])
            ->in(Scope::SINGLETON);
        $this->bind(RecordedMethods::class)->annotatedWith(Recorded::class)
            ->toInstance(new RecordedMethods(RecordedMethods::WITH_READS));
        $this->bind(BodyStoreInterface::class)->toInstance(new FileBodyStore($bodyDir));
        // The module owns the flush: the process-end write happens once per process, however
        // the request arrived (bin/observe.php, PHPUnit, ...), not inside the entry script.
        $this->bind(LogFileWriter::class)->toInstance(new LogFileWriter($this->appMeta->logDir));
        $this->bind(ShutdownFlush::class)->in(Scope::SINGLETON);
        $this->bind(SemanticLoggerInterface::class)->toProvider(DevSemanticLoggerProvider::class)->in(Scope::SINGLETON);
        $this->install(new EventSourcingModule());
    }
}
