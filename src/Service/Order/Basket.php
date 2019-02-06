<?php

declare(strict_types=1);

namespace Service\Order;

use Model;
use Service\Billing\PayWay\Card;
use Service\Billing\PayWay\IBilling;
use Service\Billing\Payer;
use Service\Communication\Communicator;
use Service\Communication\CommunicationWay\Email;
use Service\Communication\CommunicationWay\ICommunication;
use Service\Discount\Discounter;
use Service\Discount\DiscountWay\IDiscount;
use Service\Discount\DiscountWay\NullObject;
use Service\User\ISecurity;
use Service\User\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Service\Order\BasketBuilder;

class Basket
{
    /**
     * Сессионный ключ списка всех продуктов корзины
     */
    private const BASKET_DATA_KEY = 'basket';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct (SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Добавляем товар в заказ
     *
     * @param int $product
     *
     * @return void
     */
    public function addProduct (int $product): void
    {
        $basket = $this->session->get(static::BASKET_DATA_KEY, []);
        if (!in_array($product, $basket, true)) {
            $basket[] = $product;
            $this->session->set(static::BASKET_DATA_KEY, $basket);
        }
    }

    /**
     * Проверяем, лежит ли продукт в корзине или нет
     *
     * @param int $productId
     *
     * @return bool
     */
    public function isProductInBasket (int $productId): bool
    {
        return in_array($productId, $this->getProductIds(), true);
    }

    /**
     * Получаем информацию по всем продуктам в корзине
     *
     * @return Model\Entity\Product[]
     */
    public function getProductsInfo (): array
    {
        $productIds = $this->getProductIds();
        return $this->getProductRepository()->search($productIds);
    }

    /**
     * Оформление заказа
     *
     * @return void
     */
    public function checkout (BasketBuilder $basketBuilder): void
    {
        $basketBuilder->setPayer(new Card());
        $basketBuilder->setDiscounter(new NullObject());
        $basketBuilder->setCommunicator(new Email());
        $basketBuilder->setSecurity(new Security($this->session));
        $basketBuilder->setProductsInBasket($this->getProductsInfo());
        (new OrderProcess($basketBuilder))->orderProcess();
    }

    /**
     * Фабричный метод для репозитория Product
     *
     * @return Model\Repository\Product
     */
    protected function getProductRepository (): Model\Repository\Product
    {
        return new Model\Repository\Product();
    }

    /**
     * Получаем список id товаров корзины
     *
     * @return array
     */
    private function getProductIds (): array
    {
        return $this->session->get(static::BASKET_DATA_KEY, []);
    }
}
