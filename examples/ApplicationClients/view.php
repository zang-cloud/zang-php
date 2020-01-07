<?php

/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 25.3.2017.
 * Time: 22:19
 */

# First we must import the actual Avaya CPaaS library
require_once '../../connectors/ApplicationClients.php';

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try {
    # Now what we need to do is instantiate the library and set the required options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called.
    $applications = ApplicationClients::getInstance();

    # This is the best approach to setting multiple options recursively
    # Take note that you cannot set non-existing options
    # Credentials can be set in <path to application>/configuration/application.config.php and then connectors
    # automatically use it when getInstance is called but it is possible to set options in this way:
    $applications -> setOptions(array(
        "account_sid"   => $_ENV["ACCOUNT_SID"],
        "auth_token"    => $_ENV["AUTH_TOKEN"],
    ));
    
    $applicationsList = $applications -> viewApplicationClient(array(
        "ApplicationSid"      => "{ApplicationSid}",
        "ClientSid"           => "{ClientSid}"
    ));

    # If you wish to get back the full response object/array then use:
    echo "<pre>";
    print_r($applicationsList);
    echo "</pre>";

} catch (ZangException $e){
    echo "<pre>";
    print_r( "Exception message: " . $e -> getMessage() . "<br>");
    print_r( "Exception code: " . $e -> getCode() . "<br>");
    print_r(  $e -> getTrace() );
    echo "</pre>";
}