<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class DocumentDataTransferObject extends DataTransferObject
{
    /** @var null|int */
    public $id;

    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $user_name;

    /** @var null|string */
    public $original_file_name;

    /** @var null|string */
    public $file_name;

    /** @var null|string */
    public $file_type;

    /** @var null|int */
    public $file_size;

    /** @var null|string */
    public $location;

    /** @var null|string */
    public $description;

    /** @var null|string */
    public $document_holder_key;

    /** @var null|string */
    public $document_holder_type;

    /** @var null|string */
    public $created_by_user_key;

    /** @var null|\Carbon\Carbon */
    public $created_at;

    /** @var null|\Carbon\Carbon */
    public $updated_at;

    public static function create(array $data): self
    {
        return new self([
            'id'                   => $data['id'] ?? null,
            'encoded_key'          => $data['encodedKey'] ?? null,
            'user_name'            => $data['userName'] ?? null,
            'original_file_name'   => $data['originalFilename'] ?? null,
            'file_name'            => $data['name'] ?? null,
            'file_type'            => $data['type'] ?? null,
            'file_size'            => $data['fileSize'] ?? null,
            'location'             => $data['location'] ?? null,
            'description'          => $data['description'] ?? null,
            'document_holder_key'  => $data['documentHolderKey'] ?? null,
            'document_holder_type' => isset($data['documentHolderType']) ? ucfirst(strtolower($data['documentHolderType'])) : null,
            'created_by_user_key'  => $data['createdByUserKey'] ?? null,
            'created_at'           => isset($data['creationDate']) ? now()->parse($data['creationDate']) : null,
            'updated_at'           => isset($data['lastModifiedDate']) ? now()->parse($data['lastModifiedDate']) : null,
        ]);
    }
}
