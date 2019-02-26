<?php

declare(strict_types=1);

namespace Model\Repository;

use Component\Adapter\DbAdapter\IDbAdapter;
use Component\Adapter\DbAdapter\JsonAdapter;
use Model\Entity;
use Model\Entity\Product as entityProduct;
use Model\Repository\Mapper;


class ProductMapper extends Mapper
{
    /**
     * @var Entity\Product $entityProduct
     */
    private $entityProduct;


    /**
     * ProductMapper constructor.
     *
     * @param IDbAdapter $adapter
     * @param string $table
     */
    public function __construct(IDbAdapter $adapter, string $table = 'productSource')
    {
        parent::__construct($adapter, $table);
        $this->entityProduct = new entityProduct(1, 'product', 1);
    }

    /**
     * Поиск продуктов по массиву id
     *
     * @param int[] $ids
     *
     * @return Entity\Product[]
     */
    public function search(array $ids = []): array
    {
        if (!count($ids)) {
            return [];
        }
        $productList = [];
        $all = ObjectStorage::getAllObjects();
        if (count($all)) {
            /**
             * @var Entity\Entity $one
             */
            foreach ($all as $one) {
                if (in_array($one->getId(), $ids)) {
                    $productList[] = $one;
                }
            }
            return $productList;
        }
        $data = $this->adapter->getAll($this->table);
        $productFilter = function (array $data) use ($ids): bool {
            return in_array($data[key($ids)], current($ids), true);
        };
        $filteredData = array_filter($data, $productFilter);

        foreach ($filteredData as $item) {
            $product = $this->createThisEntity($item);
            $productList[] = $product;
            ObjectStorage::add($product);
        }

        return $productList;
    }

    /**
     * @param int $id
     * @param array $settings
     */
    public function update(int $id, array $settings)
    {
        /**
         * @var Entity\Product $product
         */
        $product = ObjectStorage::getObject($this->getClassName(), $id);
        $product->setName($settings['name']);
        $product->setPrice($settings['price']);
    }


    protected function createThisEntity(array $properties): Entity\Entity
    {
        $product = clone $this->entityProduct;
        $product->setId($properties['id']);
        $product->setName($properties['name']);
        $product->setPrice($properties['price']);
        return $product;
    }
}
