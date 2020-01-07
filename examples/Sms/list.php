<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 27.3.2017.
 * Time: 20:00
 */

# First we must import the actual Avaya CPaaS library
require_once '../../connectors/Sms.php';

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try {

    # Now what we need to do is instantiate the library and set the required options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called.
    $sms = Sms::getInstance();

    # This is the best approach to setting multiple options recursively
    # Take note that you cannot set non-existing options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called but it is possible to set options in this way:

    $sms -> setOptions(array(
        "account_sid"   => $_ENV["ACCOUNT_SID"],
        "auth_token"    => $_ENV["AUTH_TOKEN"],
    ));

    # NOTICE: The code below will get information for all user messages.
    $list = $sms -> listSMS(array( 'PageSize' => 10));

    # If you wish to get back the full response object/array then use:
    echo "<pre>";
    print_r($list->getResponse());
    echo "</pre>";


} catch (ZangException $e){
    echo "<pre>";
    print_r( "Exception message: " . $e -> getMessage() . "<br>");
    print_r( "Exception code: " . $e -> getCode() . "<br>");
    print_r(  $e -> getTrace() );
    echo "</pre>";
}
