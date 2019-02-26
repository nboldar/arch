<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 23.02.2019
 * Time: 21:49
 */

namespace Model\Repository;


use Component\Adapter\DbAdapter\IDbAdapter;
use Model\Entity\Entity;

abstract class Mapper
{
    public $adapter;
    protected $table;

    /**
     * DataMapper constructor.
     *
     * @param IDbAdapter $adapter
     * @param string $table
     */
    public function __construct(IDbAdapter $adapter, string $table)
    {
        $this->adapter = $adapter;
        $this->table = $table;
    }

    /**
     * @param array $properties
     *
     * @return Entity
     */
    abstract protected function createThisEntity(array $properties): Entity;

    /**
     * @param int $id
     * @param array $settings
     */
    abstract public function update(int $id, array $settings);


    /**
     * @param array $properties
     */
    public function insert(array $properties)
    {
        $product = $this->createThisEntity($properties);
        ObjectStorage::add($product);
    }


    /**
     * @param int $id
     *
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $key = $this->getClassName() . "_" . $id;
        $storage = ObjectStorage::getAllObjects();

        if (key_exists($key, $storage)) {
            unset($storage[$key]);
        } else {
            throw new \Exception("There is no item with such id as {$id}");
        }
    }

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        $all = ObjectStorage::getAllObjects();
        if (!count($all)) {
            $data = $this->adapter->getAll($this->table);
            foreach ($data as $item) {
                ObjectStorage::add($this->createThisEntity($item));
            }
            return ObjectStorage::getAllObjects();
        }
        return $all;
    }

    /**
     * @param int $id
     *
     * @return Entity
     * @throws \Exception
     */
    public function fetchOne(int $id): Entity
    {

        $productObject = ObjectStorage::getObject($this->getClassName(), $id);
        if (is_null($productObject)) {
            $result = $this->adapter->getOne($this->table, $id);
        } else {
            return $productObject;
        }
        if (is_null($result)) {
            throw new \Exception("There is no product with id={$id}");
        } else {
            return $this->createThisEntity($result);
        }
    }

    /**
     * @param int $id
     *
     * @return Entity
     * @throws \Exception
     */
    public function getById(int $id): Entity
    {
        $this->fetchOne($id);
    }


    /**
     * @return string
     */
    protected function getClassName(): string
    {
        return get_class($this);
    }
}