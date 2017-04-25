<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/Usage.php";

/**
 * @covers Usage
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class UsageTest extends TestCase {

    public function testViewUsage(){
        $instance = Usage::getInstance(array(
            "account_sid" => $_ENV["ACCOUNT_SID"],
            "auth_token" => $_ENV["AUTH_TOKEN"]
        ));
        $res = $instance->viewUsage(array(
            "UsageSid" => "TestUsageSid"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testListUsage(){
        $instance = Usage::getInstance();
        $res = $instance->listUsage(array(
            "Page" => "0",
            "PageSize" => "25",
            "Day" => "12",
            "Month" => "12",
            "Year" => "2016",
            "Product" => "3",
        ));
        $this -> assertObjectHasAttribute("usages", $res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}