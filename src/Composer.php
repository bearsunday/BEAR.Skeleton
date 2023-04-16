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
        unlink(__FILE__);
    }
}
