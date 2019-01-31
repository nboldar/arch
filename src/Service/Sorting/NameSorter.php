<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 30.01.2019
 * Time: 22:25
 */

namespace Service\Sorting;


class NameSorter implements ISorting
{
    public function sort (array $items)
    {
        usort($items, function ($item1, $item2) {
            if (substr($item1->getName(), 0, 1) == substr($item2->getName(), 0, 1)) {
                return 0;
            }
            return (substr($item1->getName(), 0, 1) < substr($item2->getName(), 0, 1)) ? -1 : 1;
        });
       // var_dump($items); exit;
        return $items;
    }

}