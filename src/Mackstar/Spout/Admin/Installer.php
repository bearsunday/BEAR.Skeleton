<?php
/**
 * This file is part of the BEAR.Package package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Admin;

use Composer\Script\Event;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Composer script
 */
class Installer
{
    /**
     * Composer post install script
     *
     * @param Event $event
     */
    public static function postInstall(Event $event = null)
    {
        $skeletonRoot = dirname(dirname(__DIR__));
        $folderName = (new \SplFileInfo($skeletonRoot))->getFilename();
        $appName = ucwords($folderName);
        $jobChmod = function (\SplFileInfo $file) {
            chmod($file, 0777);
        };
        $jobRename = function (\SplFileInfo $file) use ($appName) {
            $fineName = $file->getFilename();
            if ($file->isDir() || strpos($fineName, '.') === 0 || ! is_writable($file)) {
                return;
            }
            $contents = file_get_contents($file);
            $contents = str_replace('Mackstar', $appName, $contents);
            file_put_contents($file, $contents);
        };

        // chmod
        self::recursiveJob("{$skeletonRoot}/var/log", $jobChmod);
        self::recursiveJob("{$skeletonRoot}/var/tmp", $jobChmod);

        // rename file contents
        self::recursiveJob($skeletonRoot, $jobRename);

        // remove self (install script)
        unlink("{$skeletonRoot}/src/Mackstar/Installer.php");

        // rename app folder
        $newName = str_replace($folderName, $appName, $skeletonRoot);
        rename($skeletonRoot, $newName);
        rename("{$skeletonRoot}/src/Mackstar", "{$skeletonRoot}/src/{$appName}");

        // rename tests/Mackstar folder
        rename("{$skeletonRoot}/tests/Mackstar", "{$skeletonRoot}/tests/{$appName}");

        // symlink
        symlink("{$skeletonRoot}/var/lib/smarty/template", "{$skeletonRoot}/src/{$appName}/Resource/template");
        symlink("{$skeletonRoot}/src/{$appName}/Module/config", "{$skeletonRoot}/config");

        // remove composer.json
        unlink("$skeletonRoot/composer.json");
    }

    /**
     * @param string   $path
     * @param Callable $job
     *
     * @return void
     */
    private static function recursiveJob($path, Callable $job)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach($iterator as $file) {
            $job($file);
        }
    }
}
