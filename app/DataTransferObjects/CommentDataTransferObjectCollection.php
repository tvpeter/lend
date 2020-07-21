<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class CommentDataTransferObjectCollection extends DataTransferObjectCollection
{
    public function current(): CommentDataTransferObject
    {
        return parent::current();
    }

    public static function create(array $data): CommentDataTransferObjectCollection
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = CommentDataTransferObject::create($item);
        }

        return new self($collection);
    }
}
