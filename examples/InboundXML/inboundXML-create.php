<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 28.3.2017.
 * Time: 22:10
 */

# First we must import the actual Zang library
require_once "../../library/ZangApi/InboundXML.php";

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try {
    # Now what we need to do is instantiate the library
    $inboundXml = new Zang_InboundXML();

    # Add components to Inbound XML
    $inboundXml -> dial() -> conference( "ConferenceCall", array(
        "muted" => "true"
    ));
    $inboundXml -> say( "test", array(
        "voice" => "male"
    ));

    # If you wish to get back validated Inbound XML as string then use:
    echo $inboundXml;


} catch (Exception $e){
    echo $e->getMessage();
    echo "<br><pre>";
    print_r( $e->getTrace() );
    echo "</pre>";
}