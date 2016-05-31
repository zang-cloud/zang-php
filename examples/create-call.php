<?php

/**
 * 
 * How to make a call with Zang
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
# response_to_array to TRUE, otherwise, leave it as-is
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
    
    # NOTICE: The code bellow will initiate a new call message.
    
    # Zang_Helpers::filter_e164 is a internal, wrapper helper to help you work with phone numbers and their formatting
    # For more information about E.164, please visit: http://en.wikipedia.org/wiki/E.164
    
    $call = $zang->create('calls', array(
        'From' => '(XXX) XXX-XXXX',
        'To'   => '(XXX) XXX-XXXX',
        'Url'  => "http://www.zang.io/ivr/welcome/call"
    ));
    
    # If you wish to get the Call SID just created then use:
    print_r($call->sid);
    
    # If you wish to get back the full response object/array then use:
    print_r($call->getResponse());
    
} catch (Zang_Exception $e) {
    echo "Error occured: " . $e->getMessage() . "\n";
    exit;
}
