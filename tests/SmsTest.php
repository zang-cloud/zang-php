<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/Sms.php";

/**
 * @covers Sms
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class SmsTest extends TestCase {

    public function testViewSms(){
            $smsInstance = Sms::getInstance();
            $res = $smsInstance->viewSms(array(
                "SMSMessageSid" => "TestSmsSid"
            ));
            $this->checkResponse($res->getResponse());
    }

    public function testSendSms()
    {
            $smsInstance = Sms::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));

            $res = $smsInstance->sendSms(array(
                'To' => "+123456",
                'Body' => "test from java",
                "From" => "+654321",
                "StatusCallbackMethod" => "GET",
                'AllowMultiple' => "False"
            ));
            $this->checkResponse($res->getResponse());
    }

    public function testListSms(){
            $smsInstance = Sms::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $smsInstance->listSMS(array(
                "To" => '%2B123456',
                "Page" => "0",
                "PageSize" => "10"
            ));
            $this->assertObjectHasAttribute("sms_messages", $res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("body", $response);
        $this -> assertObjectHasAttribute("status", $response);
        $this -> assertObjectHasAttribute("direction", $response);
        $this -> assertObjectHasAttribute("date_updated", $response);
        $this -> assertObjectHasAttribute("price", $response);
        $this -> assertObjectHasAttribute("from", $response);
        $this -> assertObjectHasAttribute("uri", $response);
        $this -> assertObjectHasAttribute("account_sid", $response);
        $this -> assertObjectHasAttribute("to", $response);
        $this -> assertObjectHasAttribute("sid", $response);
        $this -> assertObjectHasAttribute("date_sent", $response);
        $this -> assertObjectHasAttribute("date_created", $response);
        $this -> assertObjectHasAttribute("api_version", $response);
    }
}