<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class LoanPurposeDataTransferObjectCollection extends DataTransferObjectCollection
{
    public function current(): LoanPurposeDataTransferObject
    {
        return parent::current();
    }

    public static function create(array $data): LoanPurposeDataTransferObjectCollection
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = LoanPurposeDataTransferObject::create($item);
        }

        return new self($collection);
    }
}
