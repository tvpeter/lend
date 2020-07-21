<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class BvnDataTransferObject extends DataTransferObject
{
    /** @var string */
    public $bvn;

    /** @var string */
    public $first_name;

    /** @var string */
    public $middle_name;

    /** @var string */
    public $last_name;

    /** @var string */
    public $date_of_birth;

    /** @var string */
    public $email;

    /** @var string */
    public $gender;

    /** @var string */
    public $mobile_number;

    /** @var string */
    public $address;

    /**
     * Create BvnUserData from BVN API response.
     *
     * @param array $data
     *
     * @return static
     */
    public static function create(array $data): self
    {
        return new self([
            'bvn'           => $data['BVN'],
            'first_name'    => isset($data['firstName']) ? ucfirst(strtolower($data['firstName'])) : '',
            'middle_name'   => isset($data['middleName']) ? ucfirst(strtolower($data['middleName'])) : '',
            'last_name'     => isset($data['lastName']) ? ucfirst(strtolower($data['lastName'])) : '',
            'date_of_birth' => isset($data['DOB']) ? self::formatDateOfBirth($data['DOB']) : '',
            'email'         => isset($data['email']) ? strtolower($data['email']) : '',
            'gender'        => $data['gender'] ?? '',
            'mobile_number' => $data['phoneNumber'] ?? '',
            'address'       => isset($data['residentialAddress']) ? ucwords(strtolower($data['residentialAddress'])) : '',
        ]);
    }

    public static function formatDateOfBirth($dateOfBirth)
    {
        $date = now()->parse($dateOfBirth);

        return $date->isFuture() ? $date->subCentury()->format('Y-m-d') : $date->format('Y-m-d');
    }
}
