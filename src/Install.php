<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use Closure;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use function array_merge;
use function chmod;
use function dirname;
use function file_get_contents;
use function file_put_contents;
use function in_array;
use function is_dir;
use function is_writable;
use function phpversion;
use function preg_replace;
use function rename;
use function sprintf;
use function str_replace;
use function strtolower;
use function substr;
use function unlink;

final class Install
{
    public function __invoke(Event $event): void
    {
        $io = $event->getIO();
        $vendor = $this->ask($io, 'What is the vendor name ?', 'MyVendor');
        $project = $this->ask($io, 'What is the project name ?', 'MyProject');
        $packageName = sprintf('%s/%s', $this->camel2dashed($vendor), $this->camel2dashed($project));
        $json = new JsonFile(Factory::getComposerFile());
        $composerJson = $this->getComposerJson($vendor, $project, $packageName, $json);
        $this->modifyFiles($vendor, $project);
        $io->write("<info>composer.json for {$packageName} is created.\n</info>");
        $json->write($composerJson);
        unlink(__FILE__);
    }

    private function ask(IOInterface $io, string $question, string $default): string
    {
        $ask = sprintf("\n<question>%s</question>\n\n(<comment>%s</comment>):", $question, $default);

        return $io->ask($ask, $default);
    }

    private function recursiveJob(string $path, callable $job): void
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file) {
            if (! in_array($file->getExtension(), ['php', 'md', 'xml'], true)) {
                continue;
            }

            $job($file);
        }
    }

    /**
     * @return array<string, string|array>
     */
    private function getComposerJson(string $vendor, string $package, string $packageName, JsonFile $json): array
    {
        $composerJson = $json->read();
        $composerJson = array_merge($composerJson, [
            'require' => ['php' => sprintf('>=%s.0', substr((string) phpversion(), 0, 3))] + (array) $composerJson['require'],
            'license' => 'proprietary',
            'name' => $packageName,
            'description' => '',
            'autoload' => ['psr-4' => ["{$vendor}\\{$package}\\" => 'src/']],
            'autoload-dev' => ['psr-4' => ["{$vendor}\\{$package}\\" => 'tests/']],
            'scripts' => array_merge($composerJson['scripts'], [
                'compile' => "./vendor/bin/bear.compile '{$vendor}\\{$package}' prod-app ./",
                'post-install-cmd' => '@composer bin all install --ansi',
                'post-update-cmd' => '@setup',
            ]),
        ]);
        unset(
            $composerJson['autoload']['files'],
            $composerJson['scripts']['pre-install-cmd'],
            $composerJson['scripts']['pre-update-cmd'],
            $composerJson['require-dev']['composer/composer']
        );

        return $composerJson;
    }

    /**
     * @psalm-return Closure(SplFileInfo ): void
     */
    private function rename(string $vendor, string $package): Closure
    {
        return static function (SplFileInfo $file) use ($vendor, $package): void {
            $file = (string) $file;
            if (is_dir($file) || ! is_writable($file)) {
                return;
            }

            $contents = (string) file_get_contents($file);
            $contents = str_replace(
                ['BEAR.Skeleton', 'BEAR\Skeleton', 'bear/skeleton'],
                ["{$vendor}.{$package}", "{$vendor}\\{$package}", strtolower("{$vendor}/{$package}")],
                $contents
            );
            file_put_contents($file, $contents);
        };
    }

    private function camel2dashed(string $name): string
    {
        return strtolower((string) preg_replace('/([^A-Z-])([A-Z])/', '$1-$2', $name));
    }

    private function modifyFiles(string $vendor, string $project): void
    {
        $projectRoot = dirname(__DIR__);
        chmod($projectRoot . '/var/tmp', 0775);
        chmod($projectRoot . '/var/log', 0775);
        $this->recursiveJob($projectRoot, $this->rename($vendor, $project));
        unlink($projectRoot . '/README.md');
        rename($projectRoot . '/README.proj.md', $projectRoot . '/README.md');
        rename($projectRoot . '/.gitattributes.txt', $projectRoot . '/.gitattributes');
    }
}
