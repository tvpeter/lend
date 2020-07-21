<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class ClientTypeDataTransferObject extends DataTransferObject
{
    /** @var null|string */
    public $id;

    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $name;

    /** @var null|string */
    public $client_type;

    /** @var null|int */
    public $index;

    /** @var null|string */
    public $description;

    /** @var null|string */
    public $created_by_user_key;

    /** @var null|bool */
    public $can_open_accounts;

    /** @var null|bool */
    public $can_guarantee;

    /** @var null|bool */
    public $require_id;

    /** @var null|bool */
    public $use_default_address;

    /** @var null|\Carbon\Carbon */
    public $created_at;

    /**
     * Create Mambu Client Type from Mambu API response.
     *
     * @param array $data
     *
     * @return static
     */
    public static function create(array $data): self
    {
        return new self([
            'id'                  => $data['id'] ?? null,
            'encoded_key'         => $data['encodedKey'] ?? null,
            'name'                => $data['name'] ?? null,
            'client_type'         => $data['clientType'] ?? null,
            'index'               => $data['index'] ?? null,
            'description'         => $data['description'] ?? null,
            'created_by_user_key' => $data['createdByUserKey'] ?? null,
            'can_open_accounts'   => $data['canOpenAccounts'] ?? null,
            'can_guarantee'       => $data['canGuarantee'] ?? null,
            'require_id'          => $data['requireID'] ?? null,
            'use_default_address' => $data['useDefaultAddress'] ?? null,
            'created_at'          => isset($data['creationDate']) ? now()->parse($data['creationDate']) : null,
        ]);
    }
}
