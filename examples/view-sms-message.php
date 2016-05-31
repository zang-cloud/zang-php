<?php

/**
 * 
 * How to view the inbound SMS messages to your Zang numbers
 * 
 * --------------------------------------------------------------------------------
 * 
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
    
    # ARRAY as the first argument is due to mutliple components in the resource uri /sms_messages/{SMSMessageSid}
    $sms_message = $zang->get(array( 'sms_messages', '{SMSMessageSid}' ));
    
    # If you wish to get back the SMS/Message SID then use:
    print_r($sms_message->sid);
    
    # If you wish to get back the full response object/array use:
    print_r($sms_message->getResponse());
    
} catch (Zang_Exception $e) {
    echo "Error occured: " . $e->getMessage() . "\n";
    exit;
}