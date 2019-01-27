<?php

declare(strict_types = 1);

namespace Service\Billing\PayWay;

class Card implements IBilling
{
    /**
     * @inheritdoc
     */
    public function pay(float $totalPrice): void
    {
        // Оплата кредитной или дебетовой картой
    }
}
