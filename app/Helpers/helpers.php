<?php

use App\DataTransferObjects\BvnDataTransferObject;
use App\Helpers\GuzzleWrapper;
use libphonenumber\PhoneNumberUtil;

function sanitizePhoneNumber($phoneNumber)
{
    $phoneUtil = PhoneNumberUtil::getInstance();
    
    $number = $phoneUtil->parse($phoneNumber, "NG");
    
    return "{$number->getCountryCode()}{$number->getNationalNumber()}";
}

function getCustomFieldValue($customFields, $customFieldId, $value = 'value')
{
    $index = collect($customFields)->where('customFieldID', $customFieldId)->first();

    return $index[$value] ?? null;
}

function messageResponse($status, $message)
{
    return [
        'status' => $status,
        'body'   => $message,
    ];
}

function activeRoute($url)
{
    return Request::fullUrl() == $url ? 'active' : "";
}

/**
 * @param string $bvn
 *
 * @return BvnDataTransferObject
 */
function getBvnDetails(string $bvn)
{
    try {
        $wrapper = new GuzzleWrapper();

        $response = $wrapper->bvnClient()->post('', [
            'http_errors' => false,
            'json'        => [
                'bvn' => $bvn,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        
        if ($responseData['status'] == 'SUCCESS' && isset($responseData['data']['lastName']) && isset($responseData['data']['firstName'])) {
            return BvnDataTransferObject::create(array_merge(['BVN' => $bvn], $responseData['data']));
        } else {
            return false;
        }
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * @param string $accountNumber
 * @param string $bankCode
 *
 * @return json
 */
function validateAccountNumber(string $accountNumber, string $bankCode)
{
    try {
        $wrapper = new GuzzleWrapper();

        $response = $wrapper->paystack()->get("/bank/resolve?account_number=$accountNumber&bank_code=$bankCode", [
            'http_errors' => false,
        ]);

        $responseData = json_decode($response->getBody(), true);

        return $responseData;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * @param string $haystack
 * @param string $needle
 */
function wordMatchCount($haystack, $needle)
{
    $haystack  = strtolower($haystack);
    $needles   = explode(' ', strtolower($needle));
    $findCount = 0;

    foreach ($needles as $needle) {
        if (strlen($needle) > 0) {
            if (strpos($haystack, $needle) !== false) {
                $findCount++;
            }
        }
    }

    return $findCount;
}

/**
 * Get fullname
 *
 * @param array $data
 *
 * @return string
 */
function getFullName($data)
{
    $fullname = '';

    if (isset($data['firstName']) || isset($data['first_name'])) {
        $fullname .= ucfirst(strtolower($data['firstName'] ?? $data['first_name']));
    }

    if (isset($data['middleName']) || isset($data['middle_name'])) {
        $fullname .= " " . ucfirst(strtolower($data['middleName'] ?? $data['middle_name']));
    }

    if (isset($data['lastName']) || isset($data['last_name'])) {
        $fullname .= " " . ucfirst(strtolower($data['lastName'] ?? $data['last_name']));
    }

    return $fullname;
}

/**
 *
 * Get number in words
 *
 * @param integer $number
 *
 * @return string
 */
function amountInWords($number)
{
    return Terbilang::make($number, ' naira only');
}

/**
 * Date formater
 *
 * @param string $date
 * @param boolean $withTime
 *
 * @return string
 */
function dateFormat($date, $withTime = true)
{
    return now()->parse($date)->format($withTime ? 'jS M, Y - h:i A' : 'jS M, Y');
}

//random string generator
function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

//random number generator
function generateRandomNumber($length)
{
    return substr(str_shuffle(str_repeat($x = '0123456789', ceil($length / strlen($x)))), 1, $length);
}

//random character generator
function generateRandomCharacter($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
