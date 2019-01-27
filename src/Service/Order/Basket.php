<?php

declare(strict_types = 1);

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
    public function __construct(SessionInterface $session)
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
    public function addProduct(int $product): void
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
    public function isProductInBasket(int $productId): bool
    {
        return in_array($productId, $this->getProductIds(), true);
    }

    /**
     * Получаем информацию по всем продуктам в корзине
     *
     * @return Model\Entity\Product[]
     */
    public function getProductsInfo(): array
    {
        $productIds = $this->getProductIds();
        return $this->getProductRepository()->search($productIds);
    }

    /**
     * Оформление заказа
     *
     * @return void
     */
    public function checkout(): void
    {
        $payer=new Payer();
        // Здесь должна быть некоторая логика выбора способа платежа
        $payer->setPayer(new Card());

        $discounter = new Discounter();
        // Здесь должна быть некоторая логика получения информации о скидки пользователя
        $discounter->setDiscounter(new NullObject());

        $communicator = new Communicator();
        // Здесь должна быть некоторая логика получения способа уведомления пользователя о покупке
        $communicator->setCommunicator(new Email());

        $security = new Security($this->session);

        $this->checkoutProcess($discounter,$billing, $security, $communication);
    }

    /**
     * Проведение всех этапов заказа
     *
     * @param Discounter $discounter,
     * @param Payer $payer,
     * @param ISecurity $security,
     * @param Communicator $communicator
     * @return void
     */
    public function checkoutProcess(
        Discounter $discounter,
        Payer $payer,
        ISecurity $security,
        Communicator $communicator
    ): void {
        $totalPrice = 0;
        foreach ($this->getProductsInfo() as $product) {
            $totalPrice += $product->getPrice();
        }

        //здесь надо получить скидку
        $discount = $discounter->getDiscount();
        $totalPrice = $totalPrice - $totalPrice / 100 * $discount;
        // здесь надо оплатить
        $payer->pay($totalPrice);

        $user = $security->getUser();
        //здесь надо уведомить пользователя
        $communicator->process($user, 'checkout_template');
    }

    /**
     * Фабричный метод для репозитория Product
     *
     * @return Model\Repository\Product
     */
    protected function getProductRepository(): Model\Repository\Product
    {
        return new Model\Repository\Product();
    }

    /**
     * Получаем список id товаров корзины
     *
     * @return array
     */
    private function getProductIds(): array
    {
        return $this->session->get(static::BASKET_DATA_KEY, []);
    }
}
