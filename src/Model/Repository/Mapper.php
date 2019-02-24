<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 23.02.2019
 * Time: 21:49
 */

namespace Model\Repository;


use Component\Adapter\DbAdapter\IDbAdapter;

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
        $this->table=$table;
    }
    abstract public function insert(array $settings);
    abstract public function delete(int $id);
    abstract public function update(array $condition, array $settings);
    abstract public function fetchAll();
    abstract public function fetchOne(int $id);
    abstract public function getById(int $id);
}