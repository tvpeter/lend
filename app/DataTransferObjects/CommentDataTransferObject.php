<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class CommentDataTransferObject extends DataTransferObject
{
    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $parent_key;

    /** @var null|string */
    public $user_key;

    /** @var null|string */
    public $text;

    /** @var null|\Carbon\Carbon */
    public $created_at;

    /** @var null|\Carbon\Carbon */
    public $updated_at;

    public static function create(array $data): self
    {
        return new self([
            'encoded_key' => $data['encodedKey'] ?? null,
            'parent_key'  => $data['parentKey'] ?? null,
            'user_key'    => $data['userKey'] ?? null,
            'text'        => $data['text'] ?? null,
            'created_at'  => isset($data['creationDate']) ? now()->parse($data['creationDate']) : null,
            'updated_at'  => isset($data['lastModifiedDate']) ? now()->parse($data['lastModifiedDate']) : null,
        ]);
    }
}
