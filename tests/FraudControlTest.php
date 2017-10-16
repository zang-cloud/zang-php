<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/FraudControl.php";

/**
 * @covers FraudControl
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class FraudControlTest extends TestCase {

    public function testBlockDestination(){
            $instance = FraudControl::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $instance->blockDestination(array(
                "CountryCode" => "HR",
                "MobileEnabled" => "false",
                "LandlineEnabled" => "true",
                "SmsEnabled" => "false"
            ));
            $this -> assertObjectHasAttribute("blocked", $res->getResponse());

    }

    public function testAuthorizeDestination(){
        $instance = FraudControl::getInstance();
        $res = $instance->authorizeDestination(array(
            "CountryCode" => "HR",
            "MobileEnabled" => "false",
            "LandlineEnabled" => "true",
            "SmsEnabled" => "false"
        ));
        $this -> assertObjectHasAttribute("authorized", $res->getResponse());
    }

    public function testExtendDestinationAuthorization(){
        $instance = FraudControl::getInstance();
        $res = $instance->extendDestinationAuthorization(array(
            "CountryCode" => "HR"
        ));
        $this -> assertObjectHasAttribute("authorized", $res->getResponse());
    }

    public function testWhitelistDestination(){
        $instance = FraudControl::getInstance();
        $res = $instance->whitelistDestination(array(
            "CountryCode" => "HR",
            "MobileEnabled" => "false",
            "LandlineEnabled" => "true",
            "SmsEnabled" => "false"
        ));
        $this -> assertObjectHasAttribute("whitelisted", $res->getResponse());
    }

    public function testListFraudControlResources(){
        $instance = FraudControl::getInstance();
        $res = $instance->listFraudControlResources(array(
            "Page" => "0",
            "PageSize" => "22",
        ));
        $this -> assertObjectHasAttribute("frauds", $res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}