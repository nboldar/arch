<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 25.02.2019
 * Time: 20:18
 */

namespace Model\Repository;


use Model\Entity\Entity;

class ObjectStorage
{
    private $allObjects = [];
    private static $instance;

    static function getInstance(): ObjectStorage
    {
        if (is_null(self::$instance)) {
            self::$instance = new ObjectStorage();
        }
        return self::$instance;
    }

    private function objectKey(Entity $object): string
    {
        return get_class($object) . "_" . $object->getId();
    }

    static function add(Entity $object)
    {
        $instance = self::getInstance();
        $key = $instance->objectKey($object);
        $instance->allObjects[$key] = $object;
    }

    static function getObject(string $className, int $id): ?Entity
    {
        $instance = self::getInstance();
        $key = $className . "_" . $id;
        if (isset($instance->allObjects[$key])) {
            return $instance->allObjects[$key];
        }
        return null;
    }

    static function getAllObjects(): array
    {
        $instance = self::getInstance();
        return $instance->allObjects;
    }

    static function remove(string $className, int $id): void
    {
        $instance = self::getInstance();
        $key = $className . "_" . $id;
        foreach ($instance->allObjects as $objectKey => $object) {
            if ($objectKey === $key) {
                unset($instance->allObjects[$objectKey]);
            }
        }
    }

}