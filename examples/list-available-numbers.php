<?php

/**
 * 
 * How to list available phone numbers
 * 
 * --------------------------------------------------------------------------------
 * 
 * @category  Zang Wrapper
 * @package   Zang
 * @author    Nevio Vesic <nevio@zang.io>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2012) Zang, Inc. <info@zang.io>
 */


# A 36 character long AccountSid is always required. It can be described
# as the username for your account
$account_sid = '{AccountSid}';

# A 34 character long AuthToken is always required. It can be described
# as your account's password
$auth_token  = '{AuthToken}';

# If you want the response decoded into an Array instead of an Object, set
# response_to_array to TRUE otherwise, leave it as-is
$response_to_array = false;


# First we must import the actual Zang library
require_once '../library/Zang.php';

# Now what we need to do is instantiate the library and set the required options defined above
$zang = Zang::getInstance();

# This is the best approach to setting multiple options recursively
# Take note that you cannot set non-existing options
$zang -> setOptions(array( 
    'account_sid'       => $account_sid, 
    'auth_token'        => $auth_token,
    'response_to_array' => $response_to_array
));

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying Zang
try {
    
    # The AvailablePhoneNumbers resource is used to help with purchasing a phone number.
    # With this resource you can list ALL available phone numbers based on the phone type and the country code
    
    # Country Code of phone number. 2 digit country code required. 
    # Always use uppercase letters such as 'US' instead of 'us'
    $country_code = strtoupper('US');
    
    # Type of phone number. At the moment we only support Local phone numbers. 
    # Soon we'll support Tollfree numbers as well.
    $phone_number_type = 'Local';
    
    # This is how you can request the available phone numbers page limited to 5 records per page.
    # array( 'available_phone_numbers', $country_code, $phone_number_type ) will create:
    # AvailablePhoneNumbers/US/Local.json for you!
    $available_numbers = $zang->get(array( 'available_phone_numbers', $country_code, $phone_number_type ), array(
        'PageSize' => 5
    ));

    # Iteration over available phone numbers
    foreach($available_numbers->items() as $number) {
        print_r($number);
    }
    
} catch (Zang_Exception $e) {
    echo "Error occured: " . $e->getMessage() . "\n";
    exit;
}