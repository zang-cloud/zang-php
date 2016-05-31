<?php

/**
 * 
 * How to buy a phone number with Zang and assign it to your account!
 * 
 * --------------------------------------------------------------------------------
 * 
 * @category  Zang Wrapper
 * @package   Zang
 * @author    Nevio Vesic <nevio@zang.io>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2012) Zang, Inc. <support@zang.io>
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
    
    # NOTICE: THIS IS AN AUTOMATED PHONE NUMBER PURCHASING SCRIPT
    
    # STEP ONE: GET THE AVAILABLE PHONE NUMBER SID
    
    # First, you must lookup the phone number you wish to buy.
    # You can do that by looking at the AvailablePhoneNumber resource:
    
    # Country Code of phone number. 2 digit country code required. 
    # Always use uppercase letters such as 'US' instead of 'us'
    $country_code = strtoupper('US');
    
    # Type of phone number. At the moment we only support Local phone numbers. 
    # Soon we'll support Tollfree numbers as well.
    $phone_number_type = 'Local';
    
    # This is how you can request the available phone numbers page limited to 1 record per page.
    # array( 'available_phone_numbers', $country_code, $phone_number_type ) will create:
    # AvailablePhoneNumbers/US/Local.json for you!
    $available_numbers = $zang->get(array( 'available_phone_numbers', $country_code, $phone_number_type ), array(
        'PageSize' => 1
    ));
    
    # This will be or NULL (if no numbers were available) or an E.164 based phone number from Zang
    $available_phone_number = null;
    
    # Because we can have multiple available phone numbers in a list if we choose 
    # we cannot access numbers as in instance mode ( $instance -> sid ) and instead
    # we need to loop thru numbers, or in this case, the AvailablePhoneNumber resource,
    # fetch first instance, access phone number value and then break.
    foreach($available_numbers->items() as $available_number) {
        $available_phone_number = $available_number->phone_number;
        break;
    }
    
    # In case there's no available_phone_number (if it's null) throw LogicException
    # LogicException is native to PHP so you don't have to worry about if that's part of
    # Zang or not.
    if(is_null($available_phone_number)) {
        throw new LogicException(
            "Available phone number cannot be found, and therefore, purchase of phone number cannot continue"
        );
    }
    
    # STEP TWO: REGISTER THE NEW PHONE NUMBER
    
    # Now what we need to do is to register the phone number by creating a new 
    # call to incoming_phone_numbers with the PhoneNumber POST parameter
    # If phone number is not available you'll see the ('Invalid Phone Number') error
    $incoming_numbers = $zang->create('incoming_phone_numbers', array(
        'PhoneNumber' => $available_phone_number
    ));

    # Iterate over incoming phone numbers
    foreach($incoming_numbers->items() as $number) {
        print_r($number);
    }
    
} catch (Exception $e) {
    echo "Error occured: " . $e->getMessage() . "\n";
    exit;
}