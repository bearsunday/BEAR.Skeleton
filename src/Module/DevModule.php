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
use BEAR\QueryRepository\DevQueryRepositoryLogModule;
use BEAR\Resource\InvokerInterface;
use Koriym\SemanticLogger\SemanticLoggerInterface;
use Ray\Di\Scope;

/** Resolved for a `dev-` prefixed context; absent from `composer install --no-dev`. */
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

        // The cache log module owns the writer and the shutdown flush (bear/event-sourcing's
        // documented pattern, vendor/bear/event-sourcing/skills/bear-observe/templates/), so
        // nothing here flushes on its own.
        $this->install(new DevQueryRepositoryLogModule($this->appMeta->logDir . '/observe'));
        // SemanticLogInvoker takes the unannotated logger; alias it to the #[CacheLog]
        // instance the module above arms and flushes, so one request writes one tree.
        $this->bind(SemanticLoggerInterface::class)
            ->toProvider(ObserveLoggerProvider::class)->in(Scope::SINGLETON);

        $this->install(new EventSourcingModule());
    }
}
