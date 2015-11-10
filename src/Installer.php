<?php

namespace BEAR\Skeleton;

use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Script\Event;

class Installer
{
    public static function preInstall(Event $event)
    {
        $io = $event->getIO();
        $vendor = self::ask($io, 'What is the vendor name ?', 'MyVendor');
        $package = self::ask($io, 'What is the pacakge name ?', 'MyPackage');

        $composerFile = Factory::getComposerFile();
        $json = new JsonFile($composerFile);
        $composerDefinition = $json->read();
        $composerDefinition['extra']['package'] = [$vendor, $package];
        $composerDefinition['license'] = 'proprietary';
        unset($composerDefinition['autoload']['files']);
        unset($composerDefinition['scripts']);
        unset($composerDefinition['require-dev']['composer/composer']);
        $composerDefinition['name'] = sprintf('%s/%s', strtolower($vendor), strtolower($package));
        $composerDefinition['description'] = '';
        $composerDefinition['license'] = 'proprietary';
        $composerDefinition['autoload']['psr-4'] = ["{$vendor}\\{$package}\\" => "src/"];
        // Update composer definition
        $json->write($composerDefinition);
        $io->write("<info>comoser.json is created.\n</info>");
    }

    public static function postInstall(Event $event = null)
    {
        $composerFile = Factory::getComposerFile();
        $json = new JsonFile($composerFile);
        $composerDefinition = $json->read();
        list($vendorName, $packageName) = $composerDefinition['extra']['package'];
        $skeletonRoot = dirname(__DIR__);
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
            $contents = str_replace('bear/greeting', strtolower("{$vendorName}/{$packageName}"), $contents);
            file_put_contents($file, $contents);
        };
        // chmod
        self::recursiveJob("{$skeletonRoot}/var/tmp", $jobChmod);
        self::recursiveJob("{$skeletonRoot}/var/log", $jobChmod);
        // rename file contents
        self::recursiveJob("{$skeletonRoot}", $jobRename);
        // renew composer.json
        $composerFile = Factory::getComposerFile();
        $json = new JsonFile($composerFile);
        $composerDefinition = $json->read();
        unset($composerDefinition['extra']);
        $json->write($composerDefinition);
        // remove installer files
        unlink($skeletonRoot . '/README.md');
        unlink(__FILE__);
        $event->getIO()->write("<info>Thank you for using BEAR.Sunday !\n</info>");
    }

    private static function ask(IOInterface $io, $question, $default)
    {
        $ask = [
            sprintf("\n<question>%s</question>\n", $question)
        ];
        $answer = $io->ask($ask, $default);

        return $answer;
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
