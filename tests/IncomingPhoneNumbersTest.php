<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/IncomingPhoneNumbers.php";

/**
 * @covers IncomingPhoneNumbers
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class IncomingPhoneNumbersTest extends TestCase {

    public function testViewIncomingNumber(){
            $instance = IncomingPhoneNumbers::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $instance->viewIncomingNumber(array(
                "IncomingNumberSid" => "TestIncomingPhoneNumberSid",
            ));
            $this->checkResponse($res->getResponse());
    }

    public function testListIncomingNumbers(){
        $instance = IncomingPhoneNumbers::getInstance();
        $res = $instance->listIncomingNumbers(array(
            "Contains" => "123",
            "FriendlyName" => "MyNumber",
            "Page" => "0",
            "PageSize" => "25"
        ));
        $this -> assertObjectHasAttribute("incoming_phone_numbers", $res->getResponse());
    }

    public function testPurchaseIncomingNumbers(){
        $instance = IncomingPhoneNumbers::getInstance();
        $res = $instance->purchaseIncomingNumbers(array(
            "FriendlyName" => "MyNumber",
            "PhoneNumber" => "+1234",
            "AreaCode" => "123",
            "VoiceCallerIdLookup" => "true",
            "VoiceApplicationSid" => "VoiceApplicationSid",
            "SmsApplicationSid" => "SmsApplicationSid",
            "VoiceUrl" => "VoiceUrl",
            "VoiceMethod" => "GET",
            "VoiceFallbackUrl" => "VoiceFallbackUrl",
            "VoiceFallbackMethod" => "GET",
            "SmsUrl" => "SmsUrl",
            "SmsMethod" => "GET",
            "SmsFallbackUrl" => "SmsFallbackUrl",
            "SmsFallbackMethod" => "POST",
            "HeartbeatUrl" => "HeartbeatUrl",
            "HeartbeatMethod" => "POST",
            "StatusCallback" => "StatusCallback",
            "StatusCallbackMethod" => "POST",
            "HangupCallback" => "HangupCallback",
            "HangupCallbackMethod" => "POST"
        ));
        $this->checkResponse($res->getResponse());
    }

    public function testUpdateIncomingNumbers(){
        $instance = IncomingPhoneNumbers::getInstance();
        $res = $instance->updateIncomingNumbers(array(
            "IncomingPhoneNumberSid" => "TestIncomingPhoneNumberSid",
            "FriendlyName" => "MyNumber",
            "VoiceCallerIdLookup" => "true",
            "VoiceUrl" => "VoiceUrl",
            "VoiceMethod" => "GET",
            "VoiceFallbackUrl" => "VoiceFallbackUrl",
            "VoiceFallbackMethod" => "GET",
            "SmsUrl" => "SmsUrl",
            "SmsMethod" => "GET",
            "SmsFallbackUrl" => "SmsFallbackUrl",
            "SmsFallbackMethod" => "POST",
            "HeartbeatUrl" => "HeartbeatUrl",
            "HeartbeatMethod" => "POST",
            "StatusCallback" => "StatusCallback",
            "StatusCallbackMethod" => "POST",
            "HangupCallback" => "HangupCallback",
            "HangupCallbackMethod" => "POST"
        ));
        $this->checkResponse($res->getResponse());
    }

    public function testDeleteIncomingNumbers(){
        $instance = IncomingPhoneNumbers::getInstance();
        $res = $instance->deleteIncomingNumbers(array(
            "IncomingPhoneNumberSid" => "TestIncomingPhoneNumberSid",
        ));
        $this->checkResponse($res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}