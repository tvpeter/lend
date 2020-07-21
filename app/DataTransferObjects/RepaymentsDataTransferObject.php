<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class RepaymentsDataTransferObject extends DataTransferObject
{
    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $state;

    /** @var null|string */
    public $state_color;

    /** @var null|float */
    public $principal_due;

    /** @var null|float */
    public $principal_paid;

    /** @var null|float */
    public $interest_due;

    /** @var null|float */
    public $interest_paid;

    /** @var null|float */
    public $penalty_due;

    /** @var null|float */
    public $penalty_paid;

    /** @var null|float */
    public $fees_due;

    /** @var null|float */
    public $fees_paid;

    /** @var null|\Carbon\Carbon */
    public $due_date;

    /** @var null|\Carbon\Carbon */
    public $last_paid_date;

    public static function create(array $data): self
    {
        return new self([
            'encoded_key'    => $data['encodedKey'] ?? null,
            'state'          => isset($data['state']) ? ucfirst(strtolower($data['state'])) : null,
            'state_color'    => self::getRepaymentStates($data['state']),
            'principal_due'  => (float) $data['principalDue'] ?? null,
            'principal_paid' => (float) $data['principalPaid'] ?? null,
            'interest_due'   => (float) $data['interestDue'] ?? null,
            'interest_paid'  => (float) $data['interestPaid'] ?? null,
            'penalty_due'    => (float) $data['penaltyDue'] ?? null,
            'penalty_paid'   => (float) $data['penaltyPaid'] ?? null,
            'fees_due'       => (float) $data['feesDue'] ?? null,
            'fees_paid'      => (float) $data['feesPaid'] ?? null,
            'due_date'       => isset($data['dueDate']) ? now()->parse($data['dueDate']) : null,
            'last_paid_date' => isset($data['lastPaidDate']) ? now()->parse($data['lastPaidDate']) : null,
        ]);
    }

    private static function getRepaymentStates($accountState)
    {
        $status = [
            'LATE'           => 'danger',
            'PAID'           => 'success',
            'PARTIALLY_PAID' => 'success',
            'PENDING'        => 'secondary',
        ];

        return $status[$accountState] ?? 'info';
    }
}
