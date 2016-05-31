<?php

/**
 * 
 * How to generate a Zang Client token for an existing Zang Application
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
# response_to_array to TRUE; otherwise, leave it as-is

$response_to_array = false;

# First we must import the actual Zang library

require_once '../library/Zang.php';

# Then instantiate the library and set the required options defined above

$zang = Zang::getInstance();

# This is the best approach to setting multiple options recursively
# Take note that you cannot set non-existing options

$zang -> setOptions(array( 
    'account_sid'       => $account_sid, 
    'auth_token'        => $auth_token,
    'response_to_array' => $response_to_array
));

# Now get the client class

$zang_client = $zang->getClient();

# Lastly, generate the client token
# In order to do so, you will need to have a valid Application and Application SID created via REST API 
# or website: https://www.zang.io/numbers/applications/

$application_sid = '{ApplicationSid}';

# In the even this wrapper enounters an error, this exception will be thrown. Always use exceptions to catch and view errors
try {
	$zang_client -> generateToken($application_sid);
} catch(Exception $e) {
	die( sprintf("We could not generate token due to : %s \n", $e->getMessage()) );
}

# If the token is created successfully, it will be printed below

echo sprintf("Client Token for Application '%s' is: '%s' \n", $application_sid, $zang_client->getToken($application_sid));





