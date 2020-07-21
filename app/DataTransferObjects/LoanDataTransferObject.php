<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class LoanDataTransferObject extends DataTransferObject
{
    /** @var null|string */
    public $id;

    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $account_holder_key;

    /** @var null|string */
    public $account_holder_type;

    /** @var null|string */
    public $account_state;

    /** @var null|string */
    public $state_color;
    
    /** @var null|string */
    public $purpose;

    /** @var null|string */
    public $product_type_key;

    /** @var null|string */
    public $name;

    /** @var null|string */
    public $amount;

    /** @var null|string */
    public $interest_rate;

    /** @var null|string */
    public $principal_balance;

    /** @var null|string */
    public $principal_due;

    /** @var null|string */
    public $principal_paid;

    /** @var null|string */
    public $interest_paid;

    /** @var null|string */
    public $fees_paid;

    /** @var null|float|int|string */
    public $total_paid;

    /** @var null|float|int|string */
    public $total_balance;

    /** @var null|int */
    public $repayment_installments;

    /** @var null|string */
    public $repayment_period_unit;

    /** @var null|string */
    public $repayment_method;

    /** @var null|string */
    public $accrued_interest;

    /** @var null|int */
    public $repayment_method_group_index;

    /** @var null|string */
    public $bank_name;

    /** @var null|string */
    public $bank_account;

    /** @var null|string */
    public $contract_signed_sms_code;

    /** @var null|array */
    public $initial_decision_fields;

    /** @var null|array */
    public $final_decision_fields;

    /** @var null|array */
    public $policy_rule_fields;

    /** @var null|array */
    public $counter_offer_fields;

    /** @var null|array */
    public $underwriting_fields;

    /** @var null|\Carbon\Carbon */
    public $created_at;

    /** @var null|\Carbon\Carbon */
    public $updated_at;

    /** @var null|\Carbon\Carbon */
    public $approved_at;

    /** @var null|\Carbon\Carbon */
    public $closed_at;

    /** @var null|\Carbon\Carbon */
    public $last_account_appraisal_date;

    public static function create(array $data): self
    {
        return new self([
            'id'                           => $data['id'] ?? null,
            'encoded_key'                  => $data['encodedKey'] ?? null,
            'account_holder_type'          => isset($data['accountHolderType']) ? ucfirst(strtolower($data['accountHolderType'])) : null,
            'account_holder_key'           => $data['accountHolderKey'] ?? null,
            'account_state'                => isset($data['accountState']) ? str_replace('_', ' ', $data['accountState']) : null,
            'product_type_key'             => $data['productTypeKey'] ?? null,
            'state_color'                  => self::getLoanState($data['accountState']),
            'name'                         => isset($data['loanName']) ? ucwords(strtolower($data['loanName'])) : null,
            'amount'                       => isset($data['loanAmount']) ? $data['loanAmount'] : null,
            'interest_rate'                => $data['interestRate'] ?? null,
            'principal_balance'            => $data['principalBalance'] ?? null,
            'principal_due'                => $data['principalDue'] ?? null,
            'principal_paid'               => $data['principalPaid'] ?? null,
            'interest_paid'                => $data['interestPaid'] ?? null,
            'accrued_interest'             => $data['accruedInterest'] ?? null,
            'fees_paid'                    => $data['feesPaid'] ?? null,
            'total_paid'                   => (($data['principalPaid'] ?? '0.00') + ($data['interestPaid'] ?? '0.00') + ($data['feesPaid'] ?? '0.00')),
            'total_balance'                => (($data['principalBalance'] ?? '0.00') + ($data['feesBalance'] ?? '0.00') + ($data['penaltyBalance'] ?? '0.00') + ($data['interestBalance'] ?? '0.00')),
            'repayment_installments'       => $data['repaymentInstallments'] ?? null,
            'repayment_period_unit'        => isset($data['repaymentPeriodUnit']) ? ucfirst(strtolower($data['repaymentPeriodUnit'])) : null,
            'repayment_method'             => isset($data['customFieldValues']) ? getCustomFieldValue($data['customFieldValues'], 'Repayment_Method_Loan_Accounts') : null,
            'repayment_method_group_index' => isset($data['customFieldValues']) ? getCustomFieldValue($data['customFieldValues'], 'Repayment_Method_Loan_Accounts', 'customFieldSetGroupIndex') : null,
            'bank_name'                    => isset($data['customFieldValues']) ? getCustomFieldValue($data['customFieldValues'], 'Repayment_Bank_Loan_Accounts') : null,
            'purpose'                    => isset($data['customFieldValues']) ? (getCustomFieldValue($data['customFieldValues'], 'Loan_Purpose') ?? getCustomFieldValue($data['customFieldValues'], 'loan_purpose')) : null,
            'bank_account'                 => isset($data['customFieldValues']) ? getCustomFieldValue($data['customFieldValues'], 'Repayment_Account_No_Loan_Accoun') : null,
            'contract_signed_sms_code'     => isset($data['customFieldValues']) ? getCustomFieldValue($data['customFieldValues'], 'contract_signed_sms_code') : null,
            'initial_decision_fields'      => isset($data['customFieldValues']) ? self::initialDecisionFields($data['customFieldValues']) : null,
            'final_decision_fields'        => isset($data['customFieldValues']) ? self::finalDecisionFields($data['customFieldValues']) : null,
            'policy_rule_fields'           => isset($data['customFieldValues']) ? self::policyRuleFields($data['customFieldValues']) : null,
            'counter_offer_fields'         => isset($data['customFieldValues']) ? self::counterOfferFields($data['customFieldValues']) : null,
            'underwriting_fields'          => isset($data['customFieldValues']) ? self::underwritingFields($data['customFieldValues']) : null,
            'created_at'                   => isset($data['creationDate']) ? now()->parse($data['creationDate']) : null,
            'updated_at'                   => isset($data['lastModifiedDate']) ? now()->parse($data['lastModifiedDate']) : null,
            'approved_at'                  => isset($data['approvedDate']) ? now()->parse($data['approvedDate']) : null,
            'closed_at'                    => isset($data['closedDate']) ? now()->parse($data['closedDate']) : null,
            'last_account_appraisal_date'  => isset($data['lastAccountAppraisalDate']) ? now()->parse($data['lastAccountAppraisalDate']) : null,
        ]);
    }

    private static function getLoanState($accountState)
    {
        $status = [
            'ACTIVE_IN_ARREARS'  => 'danger',
            'ACTIVE'             => 'success',
            'APPROVED'           => 'success',
            'CLOSED'             => 'secondary',
            'CLOSED_WRITTEN_OFF' => 'secondary',
        ];

        return $status[$accountState] ?? 'info';
    }

    private static function underwritingFields($customFieldValues)
    {
        return [
            'Underwriting Refer Level'          => getCustomFieldValue($customFieldValues, 'UnderwritingReferLevel_Loan_Acco'),
            'Underwriting Final Approver Level' => getCustomFieldValue($customFieldValues, 'UnderwritingFinalApproverLevel_L'),
            'Underwriter Assigned'              => getCustomFieldValue($customFieldValues, 'Underwriter_Assigned_Loan_Accoun'),
            'Underwriter Decision'              => getCustomFieldValue($customFieldValues, 'Underwriter_Decision_Loan_Accoun'),
            'Decision Reason'                   => getCustomFieldValue($customFieldValues, 'Decision_Reason_Loan_Accounts'),
            'Decision Comment Level 1'          => getCustomFieldValue($customFieldValues, 'Decision_Comment_Loan_Accounts'),
            'Decision Comment Level 2'          => getCustomFieldValue($customFieldValues, 'Decision_Comment_Level_2_Loan_Ac'),
            'Decision Comment Level 3'          => getCustomFieldValue($customFieldValues, 'Decision_Comment_Level_3_Loan_Ac'),
            'Level 2 Refer comments to Level 1' => getCustomFieldValue($customFieldValues, 'Level-2 Refer comments to Level-1'),
            'Level 1 Completed By'              => getCustomFieldValue($customFieldValues, 'uw_level_1_completed_by'),
            'Level 2 completed By'              => getCustomFieldValue($customFieldValues, 'uw_level_2_completed_by'),
            'Level 3 completed By'              => getCustomFieldValue($customFieldValues, 'uw_level_3_completed_by'),
        ];
    }

    private static function initialDecisionFields($customFieldValues)
    {
        return [
            'Initial Decision'      => getCustomFieldValue($customFieldValues, 'InitialDecision_Loan_Accounts'),
            'initial Decision Code' => getCustomFieldValue($customFieldValues, 'InitialDecisionCode_Loan_Account'),
            'Initial Decision Date' => getCustomFieldValue($customFieldValues, 'InitialDecisionDate_Loan_Account'),
            'Initial Decision Time' => getCustomFieldValue($customFieldValues, 'InitialDecisionTime_Loan_Account'),
        ];
    }

    private static function finalDecisionFields($customFieldValues)
    {
        return [
            'Final Decision'      => getCustomFieldValue($customFieldValues, 'FinalDecision_Loan_Accounts'),
            'Final Decision Code' => getCustomFieldValue($customFieldValues, 'FinalDecisionCode_Loan_Accounts'),
            'Final Decision Date' => getCustomFieldValue($customFieldValues, 'FinalDecisionDate_Loan_Accounts'),
            'Final Decision Time' => getCustomFieldValue($customFieldValues, 'FinalDecisionTime_Loan_Accounts'),
        ];
    }

    private static function counterOfferFields($customFieldValues)
    {
        return [
            'Counter Offer 1 Price' => getCustomFieldValue($customFieldValues, 'CounterOffer1Price_Loan_Accounts'),
            'Counter Offer 1 Term'  => getCustomFieldValue($customFieldValues, 'CounterOffer1Term_Loan_Accounts'),
            'Counter Offer 1 Value' => getCustomFieldValue($customFieldValues, 'CounterOffer1Value_Loan_Accounts'),
        ];
    }

    private static function policyRuleFields($customFieldValues)
    {
        return [
            'Reason Code 01' => getCustomFieldValue($customFieldValues, 'ReasonCode_01_Loan_Accounts'),
            'Reason Code 02' => getCustomFieldValue($customFieldValues, 'ReasonCode_02_Loan_Accounts'),
            'Reason Code 03' => getCustomFieldValue($customFieldValues, 'ReasonCode_03_Loan_Accounts'),
            'Reason Code 04' => getCustomFieldValue($customFieldValues, 'ReasonCode_04_Loan_Accounts'),
            'Reason Code 05' => getCustomFieldValue($customFieldValues, 'ReasonCode_05_Loan_Accounts'),
            'Reason Code 06' => getCustomFieldValue($customFieldValues, 'ReasonCode_06_Loan_Accounts'),
            'Reason Code 07' => getCustomFieldValue($customFieldValues, 'ReasonCode_07_Loan_Accounts'),
            'Reason Code 08' => getCustomFieldValue($customFieldValues, 'ReasonCode_08_Loan_Accounts'),
            'Reason Code 09' => getCustomFieldValue($customFieldValues, 'ReasonCode_09_Loan_Accounts'),
            'Reason Code 10' => getCustomFieldValue($customFieldValues, 'ReasonCode_10_Loan_Accounts'),
        ];
    }
}
