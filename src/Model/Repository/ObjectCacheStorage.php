<?php


namespace Model\Repository;


use AppCashe as Cache;
use Model\Entity\Entity;
use phpDocumentor\Reflection\Types\Boolean;

class ObjectCacheStorage
{
    private $cache;

    /**
     * ObjectCacheStorage constructor.
     * @param $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    private function objectKey(Entity $object): string
    {
        return get_class($object) . "_" . $object->getId();
    }

    public function add(Entity $object): Boolean
    {
        $key = $this->objectKey($object);
        if (!$this->cache->contains($key)) {
            return $this->cache->save($key, $object, 3600);
        }
        return false;
    }

    public function getObject(string $className, int $id): ?Entity
    {
        $key = $className . "_" . $id;
        return $this->cache->fetch($key);

    }

    public function remove(string $className, int $id): void
    {
        $key = $className . "_" . $id;
        $this->cache->delete($key);
    }


}
