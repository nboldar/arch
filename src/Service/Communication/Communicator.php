<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 26.01.2019
 * Time: 22:01
 */

namespace Service\Communication;


use Service\Communication\CommunicationWay\ICommunication;

class Communicator
{
    /**
     * @var ICommunication $communicator
     */
    private $communicator;

    /**
     * @return mixed
     */
    public function getCommunicator ()
    {
        return $this->communicator;
    }

    /**
     * @param mixed $communicator
     */
    public function setCommunicator (ICommunication $communicator): void
    {
        $this->communicator = $communicator;
    }




    public function process (\Model\Entity\User $user, string $templateName, array $params = [])
    {
        $this->communicator->process( $user,$templateName, $params);
    }


}