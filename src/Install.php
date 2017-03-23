<?php
namespace BEAR\Skeleton;

use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Script\Event;

final class Install
{
    public function __invoke(Event $event)
    {
        $io = $event->getIO();
        $vendor = $this->ask($io, 'What is the vendor name ?', 'MyVendor');
        $project = $this->ask($io, 'What is the project name ?', 'MyProject');
        $packageName = sprintf('%s/%s', $this->camel2dashed($vendor), $this->camel2dashed($project));
        $json = new JsonFile(Factory::getComposerFile());
        $composerDefinition = $this->getDefinition($vendor, $project, $packageName, $json);
        $this->modifyFiles($vendor, $project);
        $io->write("<info>composer.json for {$composerDefinition['name']} is created.\n</info>");
        $json->write($composerDefinition);
        unlink(__FILE__);
    }

    private function ask(IOInterface $io, string $question, string $default) : string
    {
        $ask = [
            sprintf("\n<question>%s</question>\n", $question),
            sprintf("\n(<comment>%s</comment>):", $default)
        ];
        $answer = $io->ask($ask, $default);

        return $answer;
    }

    private function recursiveJob(string $path, callable $job)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file) {
            if (! in_array($file->getExtension(), ['php', 'md'], true)) {
                continue;
            }
            $job($file);
        }
    }

    private function getDefinition(string $vendor, string $package, string $packageName, JsonFile $json) : array
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

    private function rename(string $vendor, string $package) : callable
    {
        $jobRename = function (\SplFileInfo $file) use ($vendor, $package) {
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

    private function camel2dashed(string $name) : string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $name));
    }

    private function modifyFiles(string $vendor, string $project) : void
    {
        $projectRoot = dirname(__DIR__);
        chmod($projectRoot . '/var/tmp', 0775);
        chmod($projectRoot . '/var/log', 0775);
        $this->recursiveJob("{$projectRoot}", $this->rename($vendor, $project));
        unlink($projectRoot . '/README.md');
        rename($projectRoot . '/README.proj.md', $projectRoot . '/README.md');
    }
}
