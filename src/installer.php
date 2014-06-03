<?php

namespace BEAR\Skeleton;

use Composer\Script\Event;

/**
 * This file is part of the BEAR.Skeleton package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
class Installer
{
    public static function postInstall(Event $event = null)
    {
        $skeletonRoot = dirname(__DIR__);
        $folderName = (new \SplFileInfo($skeletonRoot))->getFilename();
        $appNameRegex = '/^[A-Z]+[a-z0-9]*\.[A-Z]+[a-z0-9]*$/';
        if (! preg_match($appNameRegex, $folderName)) {
            throw new \LogicException('Application name must be in the format "Vendor.Application".');
        }
        list($vendorName, $packageName) = explode('.', $folderName);
        $jobChmod = function (\SplFileInfo $file) {
            chmod($file, 0777);
        };
        $jobRename = function (\SplFileInfo $file) use ($vendorName, $packageName) {
            $fineName = $file->getFilename();
            if ($file->isDir() || strpos($fineName, '.') === 0 || !is_writable($file)) {
                return;
            }
            $contents = file_get_contents($file);
            $contents = str_replace('BEAR.Skeleton', "{$vendorName}.{$packageName}", $contents);
            $contents = str_replace('BEAR\Skeleton', "{$vendorName}\\{$packageName}", $contents);
            $contents = str_replace('{package_name}', strtolower("{$vendorName}/{$packageName}"), $contents);
            file_put_contents($file, $contents);
        };

        // chmod
        self::recursiveJob("{$skeletonRoot}/var/tmp", $jobChmod);

        // rename file contents
        self::recursiveJob("{$skeletonRoot}", $jobRename);
        $jobRename(new \SplFileInfo("{$skeletonRoot}/build.xml"));
        $jobRename(new \SplFileInfo("{$skeletonRoot}/build/phpcs.xml"));
        $jobRename(new \SplFileInfo("{$skeletonRoot}/build/phpdox.xml"));
        $jobRename(new \SplFileInfo("{$skeletonRoot}/build/phpmd.xml"));
        $jobRename(new \SplFileInfo("{$skeletonRoot}/phpunit.xml.dist"));

        // composer.json
        unlink("{$skeletonRoot}/composer.json");
        rename("{$skeletonRoot}/project.composer.json", "{$skeletonRoot}/composer.json");
        $composerJson = file_get_contents("{$skeletonRoot}/composer.json");
        $packageNameComposerJson = str_replace('bear/skeleton', strtolower("{$vendorName}/{$packageName}"), $composerJson);
        file_put_contents("{$skeletonRoot}/composer.json", $packageNameComposerJson);

        // remove ./vendor
        $unlink = function ($dir) use (&$unlink) {
            foreach(scandir($dir) as $file) {
                if ('.' === $file || '..' === $file) continue;
                is_dir("$dir/$file") ? $unlink("$dir/$file") : unlink("$dir/$file");
            }
            rmdir($dir);
        };
        $unlink($skeletonRoot . '/vendor');
        unlink($skeletonRoot . '/README.md');
        unlink(__FILE__);
    }

    /**
     * @param string $path
     * @param Callable $job
     *
     * @return void
     */
    private static function recursiveJob($path, $job)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file) {
            $job($file);
        }
    }
}
