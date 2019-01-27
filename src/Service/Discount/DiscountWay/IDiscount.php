<?php

declare(strict_types = 1);

namespace Service\Discount\DiscountWay;

interface IDiscount
{
    /**
     * Получаем скидку в процентах
     *
     * @return float
     */
    public function getDiscount(): float;
}
