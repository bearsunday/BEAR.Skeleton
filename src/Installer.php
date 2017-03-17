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
        $json->write($composerDefinition);
        $io->write("<info>composer.json for {$composerDefinition['name']} is created.\n</info>");
    }

    public static function postInstall(Event $event = null)
    {
        list($vendorName, $packageName) = self::$packageName;
        $skeletonRoot = dirname(__DIR__);
        self::recursiveJob("{$skeletonRoot}/var/tmp", self::chmod());
        self::recursiveJob("{$skeletonRoot}/var/log", self::chmod());
        self::recursiveJob("{$skeletonRoot}", self::rename($vendorName, $packageName));
        // remove installer files
        unlink($skeletonRoot . '/README.md');
        unlink(__FILE__);
        $event->getIO()->write("<info>Thank you for using BEAR.Sunday !\n</info>");
    }

    /**
     * @param IOInterface $io
     * @param string      $question
     * @param string      $default
     *
     * @return string
     */
    private static function ask(IOInterface $io, $question, $default)
    {
        $ask = [
            sprintf("\n<question>%s</question>\n", $question),
            sprintf("\n(<comment>%s</comment>):", $default)
        ];
        $answer = $io->ask($ask, $default);

        return $answer;
    }

    /**
     * @param string   $path
     * @param callable $job
     */
    private static function recursiveJob($path, $job)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file) {
            $job($file);
        }
    }

    /**
     * @param string   $vendor
     * @param string   $package
     * @param string   $packageName
     * @param JsonFile $json
     *
     * @return array
     */
    private static function getDefinition($vendor, $package, $packageName, JsonFile $json)
    {
        $composerDefinition = $json->read();
        $composerDefinition['license'] = 'proprietary';
        unset($composerDefinition['autoload']['files'], $composerDefinition['scripts']['pre-install-cmd'], $composerDefinition['scripts']['pre-update-cmd'], $composerDefinition['scripts']['post-create-project-cmd'], $composerDefinition['require']['composer/composer']);

        $composerDefinition['name'] = $packageName;
        $composerDefinition['description'] = '';
        $composerDefinition['license'] = 'proprietary';
        $composerDefinition['autoload']['psr-4'] = ["{$vendor}\\{$package}\\" => 'src/'];

        return $composerDefinition;
    }

    /**
     * @param string $vendor
     * @param string $package
     *
     * @return \Closure
     */
    private static function rename($vendor, $package)
    {
        $jobRename = function (\SplFileInfo $file) use ($vendor, $package) {
            $fineName = $file->getFilename();
            if ($file->isDir() || strpos($fineName, '.') === 0 || ! is_writable($file)) {
                return;
            }
            $contents = file_get_contents($file);
            $contents = str_replace('BEAR.Skeleton', "{$vendor}.{$package}", $contents);
            $contents = str_replace('BEAR\Skeleton', "{$vendor}\\{$package}", $contents);
            $contents = str_replace('bear/skeleton', strtolower("{$vendor}/{$package}"), $contents);
            file_put_contents($file, $contents);
        };

        return $jobRename;
    }

    /**
     * @return \Closure
     */
    private static function chmod()
    {
        $jobChmod = function (\SplFileInfo $file) {
            chmod($file, 0777);
        };

        return $jobChmod;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private static function camel2dashed($name)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $name));
    }
}
