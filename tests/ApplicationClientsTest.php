<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/ApplicationClients.php";

/**
 * @covers ApplicationClients
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class ApplicationClientsTest extends TestCase {

    public function testCreateApplicationClient(){
            $instance = ApplicationClients::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $instance->createApplicationClient(array(
                "ApplicationSid"  => "TestApplicationSid",
                "Nickname"        => "MyApplicationClient"
            ));
            $this->checkResponse($res);

    }

    public function testViewApplicationClient(){
            $instance = ApplicationClients::getInstance();
            $res = $instance->viewApplicationClient(array(
                "ApplicationSid"      => "TestApplicationSid",
                "ClientSid"           => "TestApplicationClientSid"
            ));
            $this->checkResponse($res);

    }

    public function testListApplicationClient(){
            $instance = ApplicationClients::getInstance();
            $res = $instance->listApplicationClient(array(
                "ApplicationSid"      => "TestApplicationSid"
            ));
            $this->checkResponse($res->getResponse()->clients[0]);

    }

    private function checkResponse($response){
        $this -> assertEquals("TestApplicationClientSid", $response->sid);
        $this -> assertEquals("MyApplicationClient", $response->nickname);
    }
}