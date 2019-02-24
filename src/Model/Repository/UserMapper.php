<?php

declare(strict_types=1);

namespace Model\Repository;

use Component\Adapter\DbAdapter\IDbAdapter;
use Component\Adapter\DbAdapter\JsonAdapter;
use Model\Entity;
use mysql_xdevapi\Exception;

class UserMapper extends Mapper
{
    /**
     * Получаем пользователя по идентификатору
     *
     * @param int $id
     *
     * @return Entity\User|null
     */
    public function getById(int $id): ?Entity\User
    {
        return $this->adapter->getOne($this->table, $id);
    }

    /**
     * Получаем пользователя по логину
     *
     * @param string $login
     *
     * @return Entity\User
     */
    public function getByLogin(string $login): ?Entity\User
    {
        $result = $this->adapter->searchAll($this->table, 'login', $login);
        if (!count($result)) {
            return null;
        }
        return $result;
    }

    /**
     * Фабрика по созданию сущности пользователя
     *
     * @param array $user
     *
     * @return Entity\User
     */
    private function createUser(array $user): Entity\User
    {
        return new Entity\User(
            $user['id'],
            $user['name'],
            $user['login'],
            $user['password'],
            $this->getRoleRepository()->fetchOne($user['role'])
        );
    }

    private function getRoleRepository()
    {
        return new RoleMapper(new JsonAdapter());
    }

    /**
     * Получаем пользователей из источника данных
     *
     * @param array $settings
     *
     * @return void
     */

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

    public function fetchAll()
    {
        $userList = [];
        $data = $this->adapter->getAll($this->table);
        foreach ($data as $item) {
            $userList[] = $this->createUser($item);
        }
        return $userList;
    }

    /**
     * @param int $id
     *
     * @return Entity\User
     * @throws \Exception
     */
    public function fetchOne(int $id)
    {
        $result = $this->adapter->getOne($this->table, $id);
        if (is_null($result)) {
            throw new \Exception("There is no user with id={$id}");
        } else {
            return $this->createUser($result);
        }
    }

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     * @link https://php.net/manual/en/language.oop5.decon.php
     *
     * @param IDbAdapter $adapter
     * @param string $table
     */
    public function __construct(IDbAdapter $adapter, string $table = 'userSource')
    {
        parent::__construct($adapter, $table);
    }
}
