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
final class ConnectorLibTest extends TestCase {

    public function testBadCredentials(){
        try {
            $instance = Sms::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $instance->listSMS();
        }catch (Exception $e){
            $this->assertEquals("An error occured while querying ZangAPI with the message ' - Syntax error, malformed JSON' and the error code '500'", $e->getMessage());
        }
    }

    public function testMethodsTest(){
            $smsInstance = Sms::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"],
                "response_to_array" => true
            ));
            $res = $smsInstance->viewSms(array(
                "SMSMessageSid" => "TestSmsSid"
            ));

            $this->assertArrayHasKey("sid", $res->getResponse());
            $this->assertEquals("TestSMSSid", $res->sid);
            $this->assertRegexp("[sid]", $res->__toString());
            $this->assertEquals("/v2/Accounts/TestAccountSid/SMS/Messages/TestSMSSid", $res->attr("uri"));
            $this->assertEquals("TestSMSSid", $res->items("sid"));
    }

    public function testMethodsTest2(){
        $instance = Sms::getInstance(array(
            "response_to_array" => false
        ));
        $res = $instance->listSMS(array(
            "To" => '%2B123456',
            "Page" => "0",
            "PageSize" => "10"
        ));
        $this->assertEquals("/v2/Accounts/TestAccountSid/SMS/Messages.json", $res->attr("uri"));
        $this->assertEquals("/v2/Accounts/TestAccountSid/SMS/Messages.json", $res->items("uri"));

        try {
            echo $res->attr("PageSize");
        }catch (Exception $e){
            $this->assertEquals("Attribute you've requested 'PageSize' cannot be found. Available attributes are: 'page, num_pages, page_size, total, start, end, uri, first_page_uri, previous_page_uri, next_page_uri, last_page_uri'", $e->getMessage());
        }

    }
}