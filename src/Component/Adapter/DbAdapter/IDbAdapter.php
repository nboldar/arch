<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 09.02.2019
 * Time: 17:06
 */

namespace Component\Adapter\DbAdapter;


interface IDbAdapter
{
    public function insert (string $table, array $settings);

    public function update (string $table, array $condition, array $settings);

    public function delete (string $table, int $id);

    public function getOne (string $table, int $id);

    public function searchAll (string $table, string $columnName, $value);
    public function getAll(string $table);
}