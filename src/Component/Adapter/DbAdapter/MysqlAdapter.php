<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 09.02.2019
 * Time: 17:13
 */

namespace Component\Adapter\DbAdapter;


use Eden\Mysql\Index;

class MysqlAdapter implements IDbAdapter
{
    private $dbConnection;

    /**
     * MysqlAdapter constructor.
     */
    public function __construct (Index $indexObj)
    {
        $this->dbConnection = $indexObj;
    }

    public function insert (string $table, array $settings)
    {
        $this->dbConnection->insertRow($table, $settings);
    }

    public function update (string $table, array $condition, array $settings)
    {
        $this->dbConnection->updateRows($table, $condition, $settings);
    }


    public function delete (string $table, int $id)
    {
        $filter = ['id=%s', $id];
        $this->dbConnection->deleteRows($table, $filter);
    }

    public function getOne (string $table, int $id)
    {
        return $this->dbConnection->getRow($table, 'id', $id);
    }

    public function searchAll (string $table, string $columnName, $value)
    {
        return $this->dbConnection->getRow($table, $columnName, $value);
    }

}