<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 26.01.2019
 * Time: 21:41
 */

namespace Service\Discount;


use Service\Discount\DiscountWay\IDiscount;

class Discounter
{
    /**
     * @var IDiscount $discounter
     */
    private $discounter;

    /**
     * @return IDiscount
     */
    public function getDiscounter (): IDiscount
    {
        return $this->discounter;
    }

    /**
     * @param IDiscount $discounter
     */
    public function setDiscounter (IDiscount $discounter): void
    {
        $this->discounter = $discounter;
    }


    public function getDiscount ()
    {
        return $this->discounter->getDiscount();
    }
}