<?php

namespace App\Log;

use Illuminate\Log\Logger;

use Monolog\Handler\StreamHandler;

class ErrorLog
{
    private $config;

    /**
     * @param array $config
     * @return \Monolog\Logger
     * @throws \Exception
     */
    public function __invoke(array $config)
    {
        $this->config = $config;
        $logger = new \Monolog\Logger('default');
        $logger->pushHandler($this->getStreamHandle());
        $logger->pushProcessor(array($this,'sendWeChat'));
        return $logger;
    }

    /**
     * @return StreamHandler
     * @throws \Exception
     */
    public function getStreamHandle()
    {
        return new StreamHandler($this->config['path'], $this->config['level']);
    }

    public function sendWeChat($record)
    {
        //队列
        return $record;
    }
}