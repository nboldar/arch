<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 30.01.2019
 * Time: 21:56
 */

namespace Service\Sorting;


class PriceSorter implements ISorting
{
    /**
     * @param array $items instenceof
     *
     * @return array
     */
    public function sort (array $items)
    {
        $count = count($items);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                if ($items[$i]->getPrice() > $items[$j]->getPrice()) {
                    $temp = $items[$j];
                    $items[$j] = $items[$i];
                    $items[$i] = $temp;

                }
            }
        }

        return $items;
    }
}