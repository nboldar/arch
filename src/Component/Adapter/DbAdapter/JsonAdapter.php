<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 23.02.2019
 * Time: 19:22
 */

namespace Component\Adapter\DbAdapter;


use mysql_xdevapi\Exception;

class JsonAdapter implements IDbAdapter
{
    private $path;

    /**
     * JsonAdapter constructor.
     *
     * @param $path
     */
    public function __construct()
    {
        $this->path = $_SERVER['DOCUMENT_ROOT'] . "/../src/Model/Repository";
    }

    /**
     * @param string $table name of json-file
     * @param array $settings
     */

    public function insert(string $table, array $settings)
    {
        $data = $this->getJsonFile($table);
        $lastItem = $data[count($data) - 1];
        $id = $lastItem['id'] + 1;
        $settings['id'] = $id;
        $data[] = $settings;
        $this->writeInJsonFile($table, $data);
    }

    public function update(string $table, array $condition, array $settings)
    {
        $data = $this->getJsonFile($table);
        $keyCondition = array_key_first($condition);
        $valueCondition = $condition[$keyCondition];

        foreach ($data as $item) {
            if ($item[$keyCondition] == $valueCondition) {
                foreach ($item as $key => $value) {
                    $value = $settings[$key];
                }
            }
        }
        $this->writeInJsonFile($table, $data);
    }

    public function delete(string $table, int $id)
    {
        $data = $this->getJsonFile($table);
        foreach ($data as $key => $item) {
            if ($item['id'] == $id) {
                unset($data[$key]);
            }
        }
        $this->writeInJsonFile($table, $data);
    }

    public function getOne(string $table, int $id): array
    {
        $data = $this->getJsonFile($table);
        foreach ($data as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }

    public function searchAll(string $table, string $columnName, $value)
    {
        $data = $this->getJsonFile($table);
        $result = [];
        foreach ($data as $item) {
            if ($item[$columnName] === $value) {
                $result[] = $item;
            }
        }
        return $result;
    }

    public function getAll(string $table)
    {
       return $this->getJsonFile($table);
    }


    protected function getJsonFile(string $table): array
    {
        $file = $this->path . "/{$table}" . ".json";
        $json = file_get_contents($file);
        return json_decode($json, true);
    }

    protected function writeInJsonFile(string $table, array $data)
    {
        $file = $this->path . "/{$table}" . ".json";
        $jsonString = json_encode($data);
        file_put_contents($file, $jsonString);
    }

}