<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class LoanDataTransferObjectCollection extends DataTransferObjectCollection
{
    public function current(): LoanDataTransferObject
    {
        return parent::current();
    }

    public static function create(array $data): LoanDataTransferObjectCollection
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = LoanDataTransferObject::create($item);
        }

        return new self($collection);
    }
}
