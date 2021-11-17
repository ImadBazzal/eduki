<?php

namespace App;

class DiscountService
{
    public function applyDiscount(array $items, int $discount): array
    {
        $itemsTotal = $this->getItemsTotal($items);

        if ($itemsTotal <= $discount) {
            return array_map(function ($item) {
                $item['price_with_discount'] = 0;

                return $item;
            }, $items);
        } else {
            $discountInPercent = $discount / ($itemsTotal / 100);

            $leftover = $discount;

            $discountedItems = [];

            foreach ($items as $item) {
                if ($leftover > 0) {
                    $itemDiscount = ceil(($item['price'] / 100) * $discountInPercent);
                } else {
                    $itemDiscount = 0;
                }

                if ($leftover < $itemDiscount) {
                    $itemDiscount = $leftover;
                }

                $priceWithDiscount = $item['price'] >= $itemDiscount ? $item['price'] - $itemDiscount : 0;

                $item['price_with_discount'] = $priceWithDiscount;

                $leftover -= $itemDiscount;

                $discountedItems[] = $item;
            }

            return $discountedItems;
        }
    }

    private function getItemsTotal(array $items): int
    {
        return array_reduce($items, function (int $carry, array $item) {
            $carry += $item['price'];

            return $carry;
        }, 0);
    }

}