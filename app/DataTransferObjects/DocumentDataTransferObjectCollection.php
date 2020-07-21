<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class DocumentDataTransferObjectCollection extends DataTransferObjectCollection
{
    public function current(): DocumentDataTransferObject
    {
        return parent::current();
    }

    public static function create(array $data): DocumentDataTransferObjectCollection
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = DocumentDataTransferObject::create($item);
        }

        return new self($collection);
    }
}
