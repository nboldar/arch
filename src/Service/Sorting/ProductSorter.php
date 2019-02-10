<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 30.01.2019
 * Time: 22:45
 */

namespace Service\Sorting;


class ProductSorter
{
    public function sort (ISorting $sorter, array $items)
    {
        return $sorter->sort($items);
    }
}