<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;

#[DaggerObject]
class PhpstanOptions
{

    public function __construct(
        private string $memoryLimit = '',
        private string $level = ''
    )
    { }

    

    #[DaggerFunction]
    public function memoryLimit(string $limit): PhpstanOptions
    {
        // $this->options['memory-limit'] = $limit;
        $this->memoryLimit = $limit;
        return $this;
    }

    #[DaggerFunction]
    public function level(string $level): PhpstanOptions
    {
        // $this->options['level'] = $level;
        $this->level = $level;
        return $this;
    }

    #[DaggerFunction]
    public function noProgress(bool $progress): PhpstanOptions
    {
        $this->options['no-progress'] = $progress;
        return $this;
    }

    #[DaggerFunction]
    public function debug(string $debug): PhpstanOptions
    {
        $this->options['debug'] = $debug;
        return $this;
    }
    
    public function buildCliCommand(): array
    {
        $cmd = [];
        foreach($this->options as $option => $val) {
            $cmd[] = sprintf('--%s=%s', $option, $val);
            // $cmd .= sprintf('--%s=%s ', $option, $val);
        }
        
        return $cmd;
    }
}