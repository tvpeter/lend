<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class LoanProductDataTransferObjectCollection extends DataTransferObjectCollection
{
    public function current(): LoanProductDataTransferObject
    {
        return parent::current();
    }

    public static function create(array $data): LoanProductDataTransferObjectCollection
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = LoanProductDataTransferObject::create($item);
        }

        return new self($collection);
    }
}
