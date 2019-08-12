<?php


namespace Traits;


use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait MonologLogger
{
    public $monologLogger;

    /**
     * @param mixed $monologLogger
     */
    public function setMonologLogger(): void
    {
        $this->monologLogger = (new Logger('appLogger'));
        $this->monologLogger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));
        $this->monologLogger->pushHandler(new FirePHPHandler());
    }

}
