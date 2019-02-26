<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 06.02.2019
 * Time: 21:32
 */

namespace Service\Order;


use Model\Entity\Product;

class OrderProcess
{
    private $basketBuilder;
    private $totalPrice;

    /**
     * OrderProcess constructor.
     *
     * @param $basketBuilder
     */
    public function __construct (BasketBuilder $basketBuilder)
    {
        $this->basketBuilder = $basketBuilder;
    }

    public function orderProcess ()
    {
        $this->getTotalPrice();

        $this->pay($this->totalPrice);

        $this->informUser();
    }

    public function getTotalPrice ()
    {
        $totalPrice = 0;
        foreach ($this->basketBuilder->getProductsInBasket() as $product) {
            $totalPrice += $product->getPrice();
        }
        $discount = $this->basketBuilder->getDiscounter()->getDiscount();
        $totalPrice = $totalPrice - $totalPrice / 100 * $discount;
        $this->totalPrice = $totalPrice;
    }

    public function pay (float $totalPrice)
    {
        try {
            $this->basketBuilder->getPayer()->pay($totalPrice);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    public function informUser ()
    {
        $user = $this->basketBuilder->getSecurity()->getUser();
        try {
            $this->basketBuilder->getCommunicator()->process($user, 'checkout_template');
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }


}