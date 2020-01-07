<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 28.3.2017.
 * Time: 22:10
 */

header('Content-Type: application/xml; charset=utf-8');

# First we must import the actual Avaya CPaaS library
require_once "../../library/ZangApi/InboundXML.php";

# If an error occurs, Zang_Exception will be raised. Due to this,
# it's a good idea to always do try/catch blocks while querying ZangAPI
try
{
    # Now what we need to do is instantiate the library
    $inboundXml = new Zang_InboundXML();

    # Add components to Inbound XML
    $inboundXml->gather("", array(
        "input" => "speech",
        "method" => "GET",
        "numDigits" => "4",
        "finishOnKey" => "#",
        "timeout" => "24",
        "hints" => "search",
        "language" => "en-US",
        "action" => "http://example.com/example-callback-url/say?example=simple.xml"
    ))->say("Plese enter 4 digit pin", array(
        "voice" => "male"
    ));
    
    $inboundXml->mms("This is an MMS sent from Zang",array(
        "to"=>"+123456",
        "from"=>"+654321",
        "mediaUrl"=>"https://tinyurl.com/lpewlmo",
        "method"=>"GET"
    ));

    # If you wish to get back validated Inbound XML as string then use:
    echo $inboundXml;

}catch(Exception $e){
    echo $e->getMessage();
    echo "<br><pre>";
    print_r($e->getTrace());
    echo "</pre>";
}