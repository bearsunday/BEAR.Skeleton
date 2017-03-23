<?php
namespace BEAR\Skeleton;

use Composer\Script\Event;

final class Composer
{
    public static function install(Event $event)
    {
        (new Install)($event);
        unlink(__FILE__);
    }
}
