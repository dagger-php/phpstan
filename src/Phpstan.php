<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Doc;
use Dagger\Container;
use Dagger\Directory;
use InvalidArgumentException;

use function Dagger\dag;

#[DaggerObject]
#[Doc('Run phpstan against your php codebase')]
class Phpstan
{
    // Phpstan Args Defaults
    private string $memoryLimit = '';
    private string $level = '';
    private bool $debug = false;
    private bool $quiet = false;
    private bool $noProgress = false;
    // @todo - config file path?

    // Dagger module defaults
    private string $phpVersion = '8.4';

    // dagger call analyse --source=. --path-to-test=src stdout
    #[DaggerFunction('phpstan')]
    public function analyse(
        Directory $source,
        string $pathToTest
    ): Container {

        $cliArgs = $this->makeCliArgs();

        $cmd = array_merge(['phpstan', 'analyse'], $cliArgs, ["/tmp/app/$pathToTest"]);

        return dag()->container()
            ->from('jakzal/phpqa:php' . $this->phpVersion . '-alpine')
            ->withFile(
                '/usr/bin/composer',
                dag()->container()->from('composer:2')->file('/usr/bin/composer')
            )
            ->withMountedDirectory('/tmp/app', $source)
            ->withWorkDir('/tmp/app')
            ->withExec(['composer', 'install'])
            ->withExec($cmd);
    }

    private function makeCliArgs(): array
    {
        $cliOptions = (new PhpstanArgBuilder())
            ->memoryLimit($this->memoryLimit)
            ->level($this->level)
            ->buildCliCommand();

        return $cliOptions;
    }

    #[DaggerFunction('memoryLimit')]
    public function memoryLimit(string $limit): Phpstan
    {
        $this->memoryLimit = $limit;

        return $this;
    }

    #[DaggerFunction('level')]
    public function level(string $level): Phpstan
    {
        $this->level = $level;

        return $this;
    }

    #[DaggerFunction('noProgress')]
    public function noProgress(bool $noProgress): Phpstan
    {
        $this->noProgress = $noProgress;

        return $this;
    }

    #[DaggerFunction('debug')]
    public function debug(bool $debug): Phpstan
    {
        $this->debug = $debug;

        return $this;
    }

    #[DaggerFunction('quiet')]
    public function quiet(bool $quiet): Phpstan
    {
        $this->quiet = $quiet;

        return $this;
    }

    #[DaggerFunction('memoryLimit')]
    public function phpVersion(string $phpVersion): Phpstan
    {

        if(!in_array($phpVersion, ['7.1', '7.2', '7.3', '7.4','8.0', '8.1', '8.2', '8.3', '8.4'])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid PHP version specified'
            ));
        }

        $this->phpVersion = $phpVersion;

        return $this;
    }

}


