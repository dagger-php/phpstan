<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;

class PhpstanArgBuilder
{
    // @todo - config file path?
    private array $options = [];

    private array $argsWtihNoValue = ['debug', 'no-progress', 'quiet'];

    public function memoryLimit(string $limit): PhpstanArgBuilder
    {
        $this->options['memory-limit'] = $limit;

        return $this;
    }

    public function level(string $level): PhpstanArgBuilder
    {
        $this->options['level'] = $level;

        return $this;
    }

    public function noProgress(bool $progress): PhpstanArgBuilder
    {
        $this->options['no-progress'] = $progress;

        return $this;
    }

    public function debug(bool $debug): PhpstanArgBuilder
    {
        $this->options['debug'] = $debug;

        return $this;
    }

    public function quiet(bool $quiet): PhpstanArgBuilder
    {
        $this->options['quiet'] = $quiet;

        return $this;
    }

    public function buildCliCommand(): array
    {
        $cmd = [];
        foreach($this->options as $option => $val) {

            // Examples: --debug, --no-progress, --quiet
            if(in_array($option, $this->argsWtihNoValue) && $val === true) {
                $cmd[] = "--$option";
                continue;
            }

            if($val === '') {
                continue;
            }
            $cmd[] = sprintf('--%s=%s', $option, $val);
        }

        return $cmd;
    }
}
