<?php

/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 25.3.2017.
 * Time: 22:19
 */

# First we must import the actual Zang library
require_once '../../connectors/Call.php';

# If an error occurs, ZangApi_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try {
    # Now what we need to do is instantiate the library and set the required options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called.
    $call = Call::getInstance();

    # This is the best approach to setting multiple options recursively
    # Take note that you cannot set non-existing options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called but it is possible to set options in this way:
    $call -> setOptions(array(
        "account_sid"   => $_ENV["ACCOUNT_SID"],
        "auth_token"    => $_ENV["AUTH_TOKEN"],
    ));

    # NOTICE: The code bellow will initiate a new call message.
    
    # Zang_Helpers::filter_e164 is a internal, wrapper helper to help you work with phone numbers and their formatting
    # For more information about E.164, please visit: http://en.wikipedia.org/wiki/E.164
    $callResult = $call->listCalls(array(
        'PageSize' => 10
    ));

    # If you wish to get back the full response object/array then use:
    echo "<pre>";
    print_r($callResult->getResponse());
    echo "</pre>";

} catch (ZangException $e){
    echo "<pre>";
    print_r( "Exception message: " . $e -> getMessage() . "<br>");
    print_r( "Exception code: " . $e -> getCode() . "<br>");
    print_r(  $e -> getTrace() );
    echo "</pre>";
}