<?php
namespace BEAR\Skeleton;

use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Script\Event;

class Installer
{
    /**
     * @var array
     */
    private static $packageName;

    public static function preInstall(Event $event)
    {
        $io = $event->getIO();
        $vendorClass = self::ask($io, 'What is the vendor name ?', 'MyVendor');
        $packageClass = self::ask($io, 'What is the project name ?', 'MyProject');
        $packageName = sprintf('%s/%s', self::camel2dashed($vendorClass), self::camel2dashed($packageClass));
        $json = new JsonFile(Factory::getComposerFile());
        $composerDefinition = self::getDefinition($vendorClass, $packageClass, $packageName, $json);
        self::$packageName = [$vendorClass, $packageClass];
        // Update composer definition
        list($vendorName, $packageName) = self::$packageName;
        $skeletonRoot = dirname(__DIR__);
        chmod($skeletonRoot . '/var/tmp', 0775);
        chmod($skeletonRoot . '/var/log', 0775);
        self::recursiveJob("{$skeletonRoot}", self::rename($vendorName, $packageName));
        // remove installer files
        unlink($skeletonRoot . '/README.md');
        rename($skeletonRoot . '/README.proj.md', $skeletonRoot . '/README.md');
        $json->write($composerDefinition);
        $io->write("<info>composer.json for {$composerDefinition['name']} is created.\n</info>");
        unlink(__FILE__);
    }

    private static function ask(IOInterface $io, string $question, string $default) : string
    {
        $ask = [
            sprintf("\n<question>%s</question>\n", $question),
            sprintf("\n(<comment>%s</comment>):", $default)
        ];
        $answer = $io->ask($ask, $default);

        return $answer;
    }

    private static function recursiveJob(string $path, callable $job)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $file) {
            $job($file);
        }
    }

    private static function getDefinition(string $vendor, string $package, string $packageName, JsonFile $json) : array
    {
        $composerDefinition = $json->read();
        $composerDefinition['license'] = 'proprietary';
        $composerDefinition['name'] = $packageName;
        $composerDefinition['description'] = '';
        $composerDefinition['license'] = 'proprietary';
        $composerDefinition['autoload']['psr-4'] = ["{$vendor}\\{$package}\\" => 'src/'];
        unset(
            $composerDefinition['autoload']['files'],
            $composerDefinition['scripts']['pre-install-cmd'],
            $composerDefinition['scripts']['pre-update-cmd'],
            $composerDefinition['scripts']['post-create-project-cmd'],
            $composerDefinition['require-dev']['composer/composer']
        );

        return $composerDefinition;
    }

    private static function rename(string $vendor, string $package) : callable
    {
        $jobRename = function (\SplFileInfo $file) use ($vendor, $package) {
            $fineName = $file->getFilename();
            if (is_dir($file) || ! is_writable($file)) {
                return;
            }
            $contents = file_get_contents($file);
            $contents = str_replace(
                ['BEAR.Skeleton', 'BEAR\Skeleton', 'bear/skeleton'],
                ["{$vendor}.{$package}", "{$vendor}\\{$package}", strtolower("{$vendor}/{$package}")],
                $contents
            );
            file_put_contents($file, $contents);
        };

        return $jobRename;
    }

    private static function camel2dashed(string $name) : string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $name));
    }
}
