<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 09.02.2019
 * Time: 17:20
 */

namespace Component\Adapter\DbAdapter;


use Simplon\Postgres\Postgres;

class PostgreAdapter implements IDbAdapter
{
    private $dbConnection;

    /**
     * PostgreAdapter constructor.
     */
    public function __construct (Postgres $postgres)
    {
        $this->dbConnection = $postgres;
    }

    public function insert (string $table, array $settings)
    {
        $this->dbConnection->insert($table, $settings);
    }

    public function update (string $table, array $condition, array $settings)
    {
        $this->dbConnection->update($table, $condition, $settings);
    }


    public function delete (string $table, int $id)
    {
        $this->dbConnection->delete($table, ['id' => $id]);
    }

    public function getOne (string $table, int $id)
    {
        $query = "SElECT * FROM {$table} WHERE id=:id";
        $condition = ['id' => $id];
        return $this->dbConnection->fetchRow($query, $condition);
    }

    public function getAll (string $table, string $columnName, $value)
    {
        $query = "SElECT * FROM {$table} WHERE {$columnName} = :{$columnName}";
        $condition = [$columnName => $value];
        return $this->dbConnection->fetchRowMany($query, $condition);
    }
}