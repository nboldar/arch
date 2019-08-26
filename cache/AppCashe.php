<?php


class AppCashe
{
    public $cashe;
    private $cacheClient;

    /**
     * AppCashe constructor.
     * @param $casheDriver
     */
    public function __construct()
    {

        $this->cacheClient = new Redis();
        $this->cashe=new \Doctrine\Common\Cache\RedisCache($this->cacheClient);
    }

}
