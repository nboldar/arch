<?php

declare(strict_types=1);

namespace Service\Product;

use Component\Adapter\DbAdapter\JsonAdapter;
use Model;
use Service\Sorting\NameSorter;
use Service\Sorting\PriceSorter;
use Service\Sorting\ProductSorter;

class Product
{
    /**
     * Получаем информацию по конкретному продукту
     *
     * @param int $id
     *
     * @return Model\Entity\Product|null
     */
    public function getInfo (int $id): ?Model\Entity\Product
    {
        return $this->getProductRepository()->fetchOne($id);
    }

    /**
     * Получаем все продукты
     *
     * @param string $sortType
     *
     * @return Model\Entity\Product[]
     */
    public function getAll (string $sortType): array
    {

        $productList = $this->getProductRepository()->fetchAll();
        /**
         * @var \Kernel $app
         */
//        var_dump($app);exit;

        $sorter = new ProductSorter();
        if ($sortType == 'name') {
            $productList = $sorter->sort(new NameSorter(), $productList);
        }
        if ($sortType == 'price') {
            $productList = $sorter->sort(new PriceSorter(), $productList);
        }

        return $productList;
    }

    /**
     * Фабричный метод для репозитория Product
     *
     * @return Model\Repository\ProductMapper
     */
    protected function getProductRepository (): Model\Repository\ProductMapper
    {
        return new Model\Repository\ProductMapper((new JsonAdapter()));
    }
}
