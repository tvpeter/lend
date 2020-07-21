<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class ClientDataTransferObject extends DataTransferObject
{
    /** @var null|string */
    public $id;

    /** @var null|string */
    public $encoded_key;

    /** @var null|string */
    public $state;

    /** @var null|string */
    public $first_name;

    /** @var null|string */
    public $middle_name;

    /** @var null|string */
    public $last_name;

    /** @var null|string */
    public $fullname;

    /** @var null|string */
    public $email;

    /** @var null|string */
    public $work_email;

    /** @var null|string */
    public $mobile_phone;

    /** @var null|string */
    public $home_phone;

    /** @var null|string */
    public $work_phone;

    /** @var null|int|string */
    public $number_of_dependents;

    /** @var null|string */
    public $number_of_children;

    /** @var null|string */
    public $gender;

    /** @var null|string */
    public $document_type;

    /** @var null|string */
    public $document_id;

    /** @var null|string */
    public $address;

    /** @var null|string */
    public $address_city;

    /** @var null|string */
    public $assigned_user_key;

    /** @var null|string */
    public $assigned_branch_key;

    /** @var null|int|string */
    public $loan_cycle;

    /** @var null|int|string */
    public $group_loan_cycle;

    /** @var null|string */
    public $preferred_language;

    /** @var null|string */
    public $client_role;

    /** @var null|string */
    public $income;

    /** @var null|string */
    public $state_of_residence;

    /** @var null|string */
    public $lga_of_residence;

    /** @var null|string */
    public $employer_industry;

    /** @var null|string */
    public $company_industry;

    /** @var null|string */
    public $employer_address;

    /** @var null|string */
    public $state_of_employment;

    /** @var null|string */
    public $lga_of_employment;

    /** @var null|string */
    public $employment_status;

    /** @var null|string */
    public $marital_status;

    /** @var null|string */
    public $residential_status;

    /** @var null|string */
    public $education;

    /** @var null|string */
    public $channel;

    /** @var null|string */
    public $next_of_kin_first_name;

    /** @var null|string */
    public $next_of_kin_middle_name;

    /** @var null|string */
    public $next_of_kin_last_name;

    /** @var null|string */
    public $next_of_kin_relationship;

    /** @var null|string */
    public $next_of_kin_phone_number;

    /** @var null|string */
    public $bank_name;

    /** @var null|string */
    public $id_full_name;

    /** @var null|string */
    public $employer_name;

    /** @var null|string */
    public $company_name;

    /** @var null|int|string */
    public $number_of_employees;

    /** @var null|\Carbon\Carbon */
    public $date_moved_in;

    /** @var null|\Carbon\Carbon */
    public $salary_date;

    /** @var null|\Carbon\Carbon */
    public $bank_account_opening_date;

    /** @var null|\Carbon\Carbon */
    public $employment_start_date;

    /** @var null|\Carbon\Carbon */
    public $date_of_birth;

    /** @var null|\Carbon\Carbon */
    public $created_at;

    /** @var null|\Carbon\Carbon */
    public $updated_at;

    /** @var null|\Carbon\Carbon */
    public $approved_at;

    /** @var null|\Carbon\Carbon */
    public $activated_at;

    /**
     * Create full Mambu Client from Mambu API response.
     *
     * @param array $data
     *
     * @return static
     */
    public static function createFullClient(array $data): self
    {
        return new self([
            'id'                        => $data['client']['id'] ?? null,
            'encoded_key'               => $data['client']['encodedKey'] ?? null,
            'state'                     => isset($data['client']['state']) ? ucfirst(strtolower($data['client']['state'])) : null,
            'first_name'                => isset($data['client']['firstName']) ? ucfirst(strtolower($data['client']['firstName'])) : null,
            'middle_name'               => isset($data['client']['middleName']) ? ucfirst(strtolower($data['client']['middleName'])) : null,
            'last_name'                 => isset($data['client']['lastName']) ? ucfirst(strtolower($data['client']['lastName'])) : null,
            'fullname'                  => self::getClientFullName($data['client']),
            'email'                     => isset($data['client']['emailAddress']) ? strtolower($data['client']['emailAddress']) : null,
            'work_email'                => getCustomFieldValue($data['customInformation'], 'client_work_email'),
            'mobile_phone'          => $data['client']['mobilePhone1'] ?? null,
            'work_phone'                => getCustomFieldValue($data['customInformation'], 'client_work_phone'),
            'home_phone'                => $data['client']['homePhone'] ?? null,
            'date_of_birth'             => isset($data['client']['birthDate']) ? now()->parse($data['client']['birthDate']) : null,
            'gender'                    => isset($data['client']['gender']) ? ucfirst(strtolower($data['client']['gender'])) : null,
            'document_type'             => isset($data['idDocuments']['0']['documentType']) ? ucwords(strtolower($data['idDocuments']['0']['documentType'])) : null,
            'document_id'               => $data['idDocuments']['0']['documentId'] ?? null,
            'address'            => isset($data['addresses']['0']['line1']) ? ucwords(strtolower($data['addresses']['0']['line1'])) : null,
            'address_city'              => isset($data['addresses']['0']['city']) ? ucfirst(strtolower($data['addresses']['0']['city'])) : null,
            'assigned_user_key'         => $data['client']['assignedUserKey'] ?? null,
            'assigned_branch_key'       => $data['client']['assignedBranchKey'] ?? null,
            'loan_cycle'                => $data['client']['loanCycle'] ?? null,
            'group_loan_cycle'          => $data['client']['groupLoanCycle'] ?? null,
            'preferred_language'        => isset($data['client']['preferredLanguage']) ? ucfirst(strtolower($data['client']['preferredLanguage'])) : null,
            'client_role'               => $data['client']['clientRole']['encodedKey'] ?? null,
            'number_of_children'        => getCustomFieldValue($data['customInformation'], 'client_num_of_children'),
            'income'                    => getCustomFieldValue($data['customInformation'], 'SAL_AMT_001'),
            'state_of_residence'        => getCustomFieldValue($data['customInformation'], 'Address_State_Clients'),
            'lga_of_residence'          => getCustomFieldValue($data['customInformation'], 'Address_LGA_2_Clients'),
            'employer_industry'         => getCustomFieldValue($data['customInformation'], 'Emp_Business_Sector'),
            'company_industry'          => getCustomFieldValue($data['customInformation'], 'Emp_Business_Industry'),
            'employer_address'          => is_null(getCustomFieldValue($data['customInformation'], 'Address_Line_1_Clients')) ? null : ucwords(strtolower(getCustomFieldValue($data['customInformation'], 'Address_Line_1_Clients'))),
            'state_of_employment'       => getCustomFieldValue($data['customInformation'], 'Employer_State_Clients'),
            'lga_of_employment'         => getCustomFieldValue($data['customInformation'], 'Employer_LGA_2_Clients'),
            'employment_status'         => getCustomFieldValue($data['customInformation'], 'emp_employment_status'),
            'channel'                   => getCustomFieldValue($data['customInformation'], 'Channel_Clients'),
            'marital_status'            => getCustomFieldValue($data['customInformation'], 'MARITAL_STATUS'),
            'residential_status'        => getCustomFieldValue($data['customInformation'], 'RESIDENTIAL_STATUS'),
            'education'                 => getCustomFieldValue($data['customInformation'], 'Education_Level'),
            'number_of_dependents'      => getCustomFieldValue($data['customInformation'], 'client_num_of_dependents'),
            'next_of_kin_first_name'    => is_null(getCustomFieldValue($data['customInformation'], 'First_Name_Clients')) ? null : ucfirst(strtolower(getCustomFieldValue($data['customInformation'], 'First_Name_Clients'))),
            'next_of_kin_middle_name'   => is_null(getCustomFieldValue($data['customInformation'], 'Middle_Name_Clients')) ? null : ucfirst(strtolower(getCustomFieldValue($data['customInformation'], 'Middle_Name_Clients'))),
            'next_of_kin_last_name'     => is_null(getCustomFieldValue($data['customInformation'], 'Surname_Clients')) ? null : ucfirst(strtolower(getCustomFieldValue($data['customInformation'], 'Surname_Clients'))),
            'next_of_kin_relationship'  => getCustomFieldValue($data['customInformation'], 'Relationship_Clients'),
            'next_of_kin_phone_number'  => getCustomFieldValue($data['customInformation'], 'NOK_Telephone_Number'),
            'employer_name'             => getCustomFieldValue($data['customInformation'], 'Employer_Link_Clients', 'linkedEntityKeyValue'),
            'company_name'              => getCustomFieldValue($data['customInformation'], 'Employer_Clients'),
            'number_of_employees'       => getCustomFieldValue($data['customInformation'], 'Number_of_Employees_Clients'),
            'bank_name'                 => getCustomFieldValue($data['customInformation'], 'Bank_1_Clients'),
            'id_full_name'              => getCustomFieldValue($data['customInformation'], 'ID_FullName'),
            'bank_account_opening_date' => is_null(getCustomFieldValue($data['customInformation'], 'Account_Opening_Date')) ? null : now()->parse(getCustomFieldValue($data['customInformation'], 'Account_Opening_Date')),
            'date_moved_in'             => is_null(getCustomFieldValue($data['customInformation'], 'Date_Moved_In')) ? null : now()->parse(getCustomFieldValue($data['customInformation'], 'Date_Moved_In')),
            'salary_date'               => is_null(getCustomFieldValue($data['customInformation'], 'SAL_DTE_005')) ? null : now()->parse(getCustomFieldValue($data['customInformation'], 'SAL_DTE_005')),
            'employment_start_date'     => is_null(getCustomFieldValue($data['customInformation'], 'emp_date_of_employment')) ? null : now()->parse(getCustomFieldValue($data['customInformation'], 'emp_date_of_employment')),
            'created_at'                => isset($data['client']['creationDate']) ? now()->parse($data['client']['creationDate']) : null,
            'updated_at'                => isset($data['client']['lastModifiedDate']) ? now()->parse($data['client']['lastModifiedDate']) : null,
            'approved_at'               => isset($data['client']['approvedDate']) ? now()->parse($data['client']['approvedDate']) : null,
            'activated_at'              => isset($data['client']['activationDate']) ? now()->parse($data['client']['activationDate']) : null,
        ]);
    }

    /**
     * Create partial Mambu Client from Mambu API response.
     *
     * @param array $data
     *
     * @return static
     */
    public static function createPartialClient(array $data): self
    {
        return new self([
            'id'                  => $data['id'] ?? null,
            'encoded_key'         => $data['encodedKey'] ?? null,
            'state'               => isset($data['state']) ? ucfirst(strtolower($data['state'])) : null,
            'first_name'          => isset($data['firstName']) ? ucfirst(strtolower($data['firstName'])) : null,
            'middle_name'         => isset($data['middleName']) ? ucfirst(strtolower($data['middleName'])) : null,
            'last_name'           => isset($data['lastName']) ? ucfirst(strtolower($data['lastName'])) : null,
            'fullname'            => self::getClientFullName($data),
            'home_phone'          => $data['homePhone'] ?? null,
            'email'               => isset($data['emailAddress']) ? strtolower($data['emailAddress']) : null,
            'mobile_phone'    => $data['mobilePhone1'] ?? null,
            'date_of_birth'       => isset($data['birthDate']) ? now()->parse($data['birthDate']) : null,
            'gender'              => isset($data['gender']) ? ucfirst(strtolower($data['gender'])) : null,
            'assigned_user_key'   => $data['assignedUserKey'] ?? null,
            'assigned_branch_key' => $data['assignedBranchKey'] ?? null,
            'loan_cycle'          => $data['loanCycle'] ?? null,
            'group_loan_cycle'    => $data['groupLoanCycle'] ?? null,
            'preferred_language'  => isset($data['preferredLanguage']) ? ucfirst(strtolower($data['preferredLanguage'])) : null,
            'client_role'         => $data['clientRole']['encodedKey'] ?? null,
            'created_at'          => isset($data['creationDate']) ? now()->parse($data['creationDate']) : null,
            'updated_at'          => isset($data['lastModifiedDate']) ? now()->parse($data['lastModifiedDate']) : null,
            'approved_at'         => isset($data['approvedDate']) ? now()->parse($data['approvedDate']) : null,
            'activated_at'        => isset($data['activationDate']) ? now()->parse($data['activationDate']) : null,
        ]);
    }

    /**
     * Get client full name
     *
     * @param array $data
     *
     * @return string
     */
    public static function getClientFullName(array $data)
    {

        $fullname = '';

        if (isset($data['firstName'])) {
            $fullname .= ucfirst(strtolower($data['firstName']));
        }

        if (isset($data['middleName'])) {
            $fullname .= " " . ucfirst(strtolower($data['middleName']));
        }

        if (isset($data['lastName'])) {
            $fullname .= " " . ucfirst(strtolower($data['lastName']));
        }

        return $fullname;
    }
}
