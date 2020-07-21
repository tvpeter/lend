<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class RepaymentsDataTransferObjectCollection extends DataTransferObjectCollection
{
    public function current(): RepaymentsDataTransferObject
    {
        return parent::current();
    }

    public static function create(array $data): RepaymentsDataTransferObjectCollection
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = RepaymentsDataTransferObject::create($item);
        }

        return new self($collection);
    }
}
