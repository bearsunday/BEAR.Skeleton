<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use Composer\Script\Event;

use function dirname;
use function exec;
use function passthru;
use function unlink;

final class Composer
{
    public static function install(Event $event): void
    {
        (new Install())($event);
        unlink(__FILE__);
    }

    public static function postInstall(Event $event): void
    {
        passthru(dirname(__DIR__) . '/vendor/bin/composer install');
        // cleanup code
        exec(dirname(__DIR__) . '/vendor/bin/phpcbf');
        $event->getIO()->write('<info>Thank you for installing BEAR.Sunday.</info>');
        $event->getIO()->write('<info>Read the README to run your application.</info>');
    }
}
