<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use Composer\Script\Event;

use function dirname;
use function passthru;
use function unlink;

final class Composer
{
    public static function install(Event $event): void
    {
        (new Install())($event);
        unlink(dirname(__DIR__) . '/.travis.yml');
        unlink(__FILE__);
    }

    public static function postInstall(Event $event): void
    {
        passthru(__DIR__ . '/vendor/bin/phpcbf');
        passthru(__DIR__ . '/vendor/bin/composer dump-autoload');
        $event->getIO()->write('<info>Thank you for install BEAR.Sunday.</info>');
    }
}
