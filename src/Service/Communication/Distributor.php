<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 27.01.2019
 * Time: 19:12
 */

namespace Service\Communication;


use Service\Communication\CommunicationWay\ICommunication;

class Distributor implements ICommand
{
    /**
     * @var ICommunication
     */
    private $sender;

    public function __construct (ICommunication $sender)
    {
        $this->sender = $sender;
    }

    public function process (\Model\Entity\User $user, string $templateName, array $params = [])
    {
       $this->sender->process($user, $templateName);
    }

}