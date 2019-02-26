<?php

declare(strict_types=1);

namespace Model\Repository;

use Component\Adapter\DbAdapter\IDbAdapter;
use Component\Adapter\DbAdapter\JsonAdapter;
use Model\Entity;


class UserMapper extends Mapper
{
    /**
     * UserMapper constructor.
     *
     * @param IDbAdapter $adapter
     * @param string $table
     */
    public function __construct(IDbAdapter $adapter, string $table = 'userSource')
    {
        parent::__construct($adapter, $table);
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
     * @param int $id
     * @param array $settings
     */
    public function update(int $id, array $settings)
    {
        /**
         * @var Entity\User $user
         */
        $user = ObjectStorage::getObject($this->getClassName(), $id);
        $user->setName($settings['name']);
        $user->setLogin($settings['price']);
        $user->setPasswordHash($settings['password']);
        $user->setRole($settings['role']);
    }


    /**
     * Фабрика по созданию сущности пользователя
     *
     * @param array $properties
     *
     * @return Entity\User
     * @throws \Exception
     */
    protected function createThisEntity(array $properties): Entity\Entity
    {
        return new Entity\User(
            $properties['id'],
            $properties['name'],
            $properties['login'],
            $properties['password'],
            $this->getRoleRepository()->fetchOne($properties['role'])
        );
    }

    /**
     * @return RoleMapper
     */
    private function getRoleRepository()
    {
        return new RoleMapper(new JsonAdapter());
    }
}
