<?php

namespace application;

class first
{
    private $logger;

    public function __construct(\vendor\Log\LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function doSomething()
    {
        if ($this->logger) {
            $this->logger->info('Doing work');
        }
    }

    public function error(){
        if($this->logger) {
            $this->logger->error('hi {us}', ['us' => 'user aa']);
        }
    }
}