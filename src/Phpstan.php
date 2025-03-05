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
    // dagger call analyze --php-version=8.4 --source=repo_url#branch --path-to-test=src stdout
    // dagger call analyze --source=https://github.com/dragoonis/Sylius#2.0 --php-version=8.3 --path-to-test=src stdout
    #[DaggerFunction('phpstan')]
    public function analyze(
        string $phpVersion,
        Directory $source,
        string $pathToTest,
    ): Container {

        if(!in_array($phpVersion, ['7.1', '7.2', '7.3', '7.4','8.0', '8.1', '8.2', '8.3', '8.4'])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid PHP version specified'
            ));
        }

        $dockerTag = sprintf("php%s-alpine", $phpVersion);

        $cliOptions = (new PhpstanOptions())->level("5")->memoryLimit('1G')->buildCliCommand();

        $cmd = ['phpstan', 'analyse'];
        $cmd = array_merge($cmd, $cliOptions);
        $cmd[] = "/tmp/app/$pathToTest";

        return dag()->container()
            ->from("jakzal/phpqa:$dockerTag")
            ->withFile(
                '/usr/bin/composer',
                dag()->container()->from('composer:2')->file('/usr/bin/composer')
            )
            ->withMountedDirectory('/tmp/app', $source)
            ->withWorkDir('/tmp/app')
            ->withExec(['composer', 'install'])
            ->withExec($cmd);

    }

}


