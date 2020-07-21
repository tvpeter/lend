<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class LoanProductDataTransferObject extends DataTransferObject
{
    /** @var null|string */
    public $id;

    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $name;

    /** @var null|string */
    public $interest_rate;

    /** @var null|string */
    public $default_interest_rate;

    /** @var null|string */
    public $default_loan_amount;

    /** @var null|string */
    public $min_loan_amount;

    /** @var null|string */
    public $max_loan_amount;

    /** @var null|string */
    public $repayment_period_units;

    /** @var null|integer */
    public $default_num_installments;

    /** @var null|integer */
    public $min_num_installments;

    /** @var null|integer */
    public $max_num_installments;

    /** @var null|\Carbon\Carbon */
    public $created_at;

    /** @var null|\Carbon\Carbon */
    public $updated_at;

    public static function create(array $data)
    {
        return new self([
            'id'                       => $data['id'] ?? null,
            'encoded_key'              => $data['encodedKey'] ?? null,
            'name'                     => $data['productName'] ?? null,
            'interest_rate'            => $data['interestRateSettings']['minInterestRate'] ?? null,
            'default_interest_rate'    => $data['interestRateSettings']['defaultInterestRate'] ?? null,
            'default_loan_amount'      => $data['defaultLoanAmount'] ?? null,
            'min_loan_amount'          => $data['minLoanAmount'] ?? null,
            'max_loan_amount'          => $data['maxLoanAmount'] ?? null,
            'repayment_period_units'   => isset($data['repaymentPeriodUnit']) ? ucfirst(strtolower($data['repaymentPeriodUnit'])) : null,
            'default_num_installments' => $data['defaultNumInstallments'] ?? null,
            'min_num_installments'     => $data['minNumInstallments'] ?? null,
            'max_num_installments'     => $data['maxNumInstallments'] ?? null,
            'created_at'               => isset($data['creationDate']) ? now()->parse($data['creationDate']) : null,
            'updated_at'               => isset($data['lastModifiedDate']) ? now()->parse($data['lastModifiedDate']) : null,
        ]);
    }
}
