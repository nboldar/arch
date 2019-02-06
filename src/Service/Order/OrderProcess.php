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
        $totalPrice = 0;
        $basket = $this->basketBuilder;
        /**
         * @var Product $product
         */
        foreach ($basket->getProductsInBasket() as $product) {
            $totalPrice += $product->getPrice();
        }

        //здесь надо получить скидку
        $discount = $basket->getDiscounter()
            ->getDiscount();
        $totalPrice = $totalPrice - $totalPrice / 100 * $discount;
        // здесь надо оплатить
        $basket->getPayer()->pay($totalPrice);

        $user = $basket->getSecurity()
            ->getUser();
        //здесь надо уведомить пользователя
        $basket->getCommunicator()
            ->process($user, 'checkout_template');
    }

}