<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use Composer\Script\Event;

use function dirname;
use function unlink;

final class Composer
{
    public static function install(Event $event): void
    {
        (new Install())($event);
        unlink(dirname(__DIR__) . '/.travis.yml');
        unlink(__FILE__);
    }
}
