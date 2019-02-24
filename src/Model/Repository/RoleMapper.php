<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 24.02.2019
 * Time: 21:25
 */

namespace Model\Repository;


use Component\Adapter\DbAdapter\IDbAdapter;
use Model\Entity\Role;

class RoleMapper extends Mapper
{

    /**
     * RoleMapper constructor.
     *
     * @param IDbAdapter $adapter
     * @param string $table
     */
    public function __construct(IDbAdapter $adapter, string $table = 'roleSource')
    {
        parent::__construct($adapter, $table);
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

    public function fetchAll()
    {
        $roleList = [];
        $data = $this->adapter->getAll($this->table);
        foreach ($data as $item) {
            $roleList[] = new Role($item['id'], $item['title'], $item['type']);
        }
        return $roleList;
    }

    public function fetchOne(int $id)
    {
        $data=$this->adapter->getOne($this->table, $id);
        return new Role($data['id'], $data['title'], $data['type']);
    }

    public function getById(int $id)
    {
        return $this->fetchOne($id);
    }

}