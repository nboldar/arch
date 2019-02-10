<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 27.01.2019
 * Time: 11:59
 */

namespace Service\Billing;


use Service\Billing\PayWay\IBilling;

class Payer
{
    /**
     * @var IBilling $payer
     */
    private $payer;

    /**
     * @return mixed
     */
    public function getPayer ()
    {
        return $this->payer;
    }

    /**
     * @param mixed $payer
     */
    public function setPayer (IBilling $payer): void
    {
        $this->payer = $payer;
    }

    public function pay (float $totalPrice): void
    {
        $this->payer->pay($totalPrice);
    }

}