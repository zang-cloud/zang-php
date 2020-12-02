<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/library/ZangApi/InboundXML.php";

/**
 * @covers Zang_InboundXML
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class InboundXMLTest extends TestCase {

    public function testCreateInboundXML(){
        $inboundXml = new Zang_InboundXML();
        $inboundXml -> dial() -> conference( "ConferenceCall", array(
            "muted" => "true"
        ));
        $inboundXml -> say( "test", array(
            "voice" => "male"
        ));

        $this->checkResponse($inboundXml->__toString());
    }

    public function testCreateConnectInboundXML(){
        $inboundXml = new Zang_InboundXML();
        $inboundXml->connect("", array(
            "action" => "http://example.com/example-callback-url/say?example=simple.xml",
            "method" => "POST"
        ))->agent("1234", array());

        $this -> assertXmlStringEqualsXmlString('<?xml version="1.0"?>
        <Response><Connect action="http://example.com/example-callback-url/say?example=simple.xml" method="POST"><Agent>1234</Agent></Connect></Response>'
        , $inboundXml->__toString());
    }

    public function testCreateInboundXMLInvalidName(){
        try {
            $inboundXml = new Zang_InboundXML("<Response>");
            $inboundXml->dial()->conference("ConferenceCall", array(
                "muted" => "true"
            ));
            $inboundXml->say("test", array(
                "voice" => "male"
            ));
        } catch (Exception $e){
            $this -> assertEquals("InboundXML Invalid construction argument", $e ->getMessage());
        }
    }

    public function testCreateInboundXMLInvalidNesting(){
        try {
            $inboundXml = new Zang_InboundXML();
            $inboundXml->dial();
            $inboundXml->conference("ConferenceCall", array(
                "muted" => "true"
            ));
            $inboundXml->say("test", array(
                "voice" => "male"
            ));
        } catch (Exception $e){
            $this -> assertEquals("InboundXML element 'Response' does not support 'Conference' element. The following elements are supported: 'Say, Play, Answer, Gather, GetSpeech, Record, PlayLastRecording, Dial, Hangup, Ping, Redirect, Reject, Pause, Sms, Mms, Connect'.", $e ->getMessage());
        }
    }

    public function testCreateInboundXMLInvalidVerb(){
        try {
            $inboundXml = new Zang_InboundXML();
            $inboundXml->dialing();
            $inboundXml->conference("ConferenceCall", array(
                "muted" => "true"
            ));
            $inboundXml->say("test", array(
                "voice" => "male"
            ));
        } catch (Exception $e){
            $this -> assertEquals("Verb 'Dialing' is not a valid InboundXML verb. Available verbs are: 'Response, Conference, Dial, Gather, Agent, Connect, GetSpeech, Hangup, Mms, Number, User, Pause, Ping, Play, PlayLastRecording, Answer, Record, Redirect, Reject, Say, Sip, Sms'", $e ->getMessage());
        }
    }

    public function testCreateInboundXMLInvalidAttribute(){
        try {
            $inboundXml = new Zang_InboundXML();
            $inboundXml -> dial() -> conference( "ConferenceCall", array(
                "mut" => "true"
            ));
            $inboundXml -> say( "test", array(
                "voice" => "male"
            ));
        } catch (Exception $e){
            $this -> assertEquals("Attribute 'mut' does not exist for verb 'Conference'. Available attributes are: 'muted, beep, startConferenceOnEnter, endConferenceOnExit, maxParticipants, timeLimit, waitUrl, waitMethod, waitSound, hangupOnStar, callbackUrl, callbackMethod, method, digitsMatch, stayAlone, record, recordCallbackUrl, recordFileFormat, recordCallbackMethod'", $e ->getMessage());
        }
    }

    public function testCreateInboundXMLInvalidValidation(){
        try {
            $inboundXml = new Zang_InboundXML();
            $inboundXml -> dial() -> conference( "ConferenceCall", array(
                "muted" => "true"
            ));
            $inboundXml -> say( "test", array(
                "voice" => "males"
            ));
            $this -> assertTrue(strpos($inboundXml -> __toString(), "Element 'Say', attribute 'voice': 'males' is not a valid value of the atomic type 'say_voice'.") !== false);
        } catch (ZangException $e){
            $this -> assertEquals("InboundXML did not pass validation!", $e ->getMessage());
        }
    }

    private function checkResponse($response){
        $this -> assertXmlStringEqualsXmlString('<?xml version="1.0"?>
<Response><Dial><Conference muted="true">ConferenceCall</Conference></Dial><Say voice="male">test</Say></Response>
', $response);
    }
}