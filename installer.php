<?php
/**
 * This file is part of the BEAR.Skeleton package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

class Installer
{
    public static function postInstall()
    {
        $skeletonRoot = __DIR__;
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
            $contents = str_replace('Skeleton', $appName, $contents);
            file_put_contents($file, $contents);
        };

        // chmod
        self::recursiveJob("{$skeletonRoot}/var/log", $jobChmod);
        self::recursiveJob("{$skeletonRoot}/var/tmp", $jobChmod);

        // rename file contents
        self::recursiveJob($skeletonRoot, $jobRename);

        // rename app folder
        $newName = str_replace($folderName, $appName, $skeletonRoot);
        rename($skeletonRoot, $newName);
        rename("{$skeletonRoot}/src/BEAR/Skeleton", "{$skeletonRoot}/src/{$folderName}/{$appName}");

        // rename tests/Skeleton folder
        rename("{$skeletonRoot}/tests/BEAR/Skeleton", "{$skeletonRoot}/tests/{$folderName}/{$appName}");

        // symlink
        //symlink("{$skeletonRoot}/var/lib/smarty/template", "{$skeletonRoot}/src/{$appName}/Resource/template");
        //symlink("{$skeletonRoot}/src/{$appName}/Module/config", "{$skeletonRoot}/config");

        // remove composer.json
        unlink("$skeletonRoot/composer.json");

        // remove self (install script)
        unlink(__FILE__);
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

Installer::postInstall();
