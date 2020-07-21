<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class LoanPurposeDataTransferObject extends DataTransferObject
{
    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $name;

    public static function create(array $data)
    {
        return new self([
            'encoded_key' => $data['encodedKey'] ?? null,
            'name'        => $data['value'] ?? null,
        ]);
    }
}
