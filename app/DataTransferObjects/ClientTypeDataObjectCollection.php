<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class ClientTypeDataObjectCollection extends DataTransferObjectCollection
{
    public function current(): ClientTypeDataTransferObject
    {
        return parent::current();
    }

    public static function create(array $data): ClientTypeDataObjectCollection
    {
        $collection = [];

        foreach ($data as $item) {
            $collection[] = ClientTypeDataTransferObject::create($item);
        }

        return new self($collection);
    }
}
