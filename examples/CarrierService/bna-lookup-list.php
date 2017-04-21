<?php

/**
 * 
 * How to request carrier lookup against specific phone number
 * 
 * --------------------------------------------------------------------------------
 * 
 * 
 * @category  ZangApi Wrapper
 * @package   ZangApi
 * @author    Nevio Vesic
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2017) Zang
 */

# First we must import the actual ZangAPI library
require_once '../../connectors/CarrierService.php';

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try {
    # Now what we need to do is instantiate the library and set the required options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called.
    $carrier = CarrierService::getInstance();

    # This is the best approach to setting multiple options recursively
    # Take note that you cannot set non-existing options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called but it is possible to set options in this way:
    $carrier -> setOptions(array(
        "account_sid"   => ACCOUNT_SID,
        "auth_token"    => AUTH_TOKEN,
    ));
    
    # The code bellow will fetch the carrier lookup record
    $carrierRes = $carrier->BnaLookupList(array(
        'PageSize' => "{PageSize}"
    ));
    
    # Printing response object
    echo "<pre>";
    print_r($carrierRes->getResponse());
    echo "</pre>";

} catch (ZangException $e) {
    echo "Error occured: " . $e->getMessage() . "\n";
    exit;
}