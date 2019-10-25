<?php

namespace App\Log;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Log\Logger;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;

class ErrorLog
{
    private $config;

    /**
     * @param Logger $logger
     * @throws \Exception
     */
    public function __invoke($logger)
    {
        $logger->listen($this->sendWeChat());
    }

    /**
     * @return StreamHandler
     * @throws \Exception
     */
    public function getStreamHandle()
    {
        return new StreamHandler($this->config['path'], $this->config['level']);
    }

    public function sendWeChat()
    {
        return function (MessageLogged $messageLogged) {
            $level = mb_strtoupper($messageLogged->level);
            $a = constant('\\Monolog\\Logger::'.$level);
            if ($a) {
                $a = 1;
                $b = $a + 1;
            };
        };
    }
}