<?php

declare(strict_types=1);

namespace DaggerModule;

class PhpstanOptions
{
    private array $options = [];
    
    public function memoryLimit(string $limit): self
    {
        $this->options['memory-limit'] = $limit;
        return $this;
    }
    
    public function level(string $level): self
    {
        $this->options['level'] = $level;
        return $this;
    }
    
    public function noProgress(bool $progress): self
    {
        $this->options['no-progress'] = $progress;
        return $this;
    }
    
    public function debug(string $debug): self
    {
        $this->options['debug'] = $debug;
        return $this;
    }
    
    public function buildCliCommand(): string
    {
        $cmd = '';
        foreach($this->options as $option => $val) {
            
            $cmd .= sprintf('--%s=%s ', $option, $val);
            
        }
        
        return $cmd;
    }
}