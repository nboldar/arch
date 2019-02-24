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
        $data = $this->adapter->getAll($this->table);
        $productFilter = function (array $data) use ($ids): bool {
            return in_array($data[key($ids)], current($ids), true);
        };
        $filteredData = array_filter($data, $productFilter);

        foreach ($filteredData as $item) {
            $productList[] = $this->cloneProduct($item);
        }

        return $productList;
    }

    /**
     * Получаем все продукты
     *
     * @return Entity\Product[]
     */
    public function fetchAll(): array
    {
        $productList = [];
        $data = $this->adapter->getAll($this->table);
        foreach ($data as $item) {
            $productList[] = $this->cloneProduct($item);
        }

        return $productList;
    }

    protected function cloneProduct(array $item)
    {
        $product = clone $this->entityProduct;
        $product->setId($item['id']);
        $product->setName($item['name']);
        $product->setPrice($item['price']);
        return $product;
    }


    public function insert(array $settings)
    {
        $this->adapter->insert($this->table, $settings);
    }

    public function delete(int $id)
    {
        $this->adapter->delete($this->table, $id);
    }

    public function update(array $condition, array $settings)
    {
        $this->adapter->update($this->table, $condition, $settings);
    }

    /**
     * @param int $id
     *
     * @return entityProduct
     * @throws \Exception
     */
    public function fetchOne(int $id): entityProduct
    {

            $result = $this->adapter->getOne($this->table, $id);
        if (is_null($result)) {
            throw new \Exception("There is no product with id={$id}");
        } else {
            return $this->cloneProduct($result);
        }
    }

    public function getById(int $id)
    {
        $this->fetchOne($id);
    }
}
