<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/SipCredentials.php";

/**
 * @covers SipCredentials
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class SipCredentialsTest extends TestCase {

    public function testViewCredentialsList(){
        $instance = SipCredentials::getInstance(array(
            "account_sid" => $_ENV["ACCOUNT_SID"],
            "auth_token" => $_ENV["AUTH_TOKEN"]
        ));
        $res = $instance->viewCredentialsList(array(
            "CLSid" => "TestCredentialsListSid"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testListCredentialsList(){
        $instance = SipCredentials::getInstance();
        $res = $instance->listCredentialsList();
        $this -> assertObjectHasAttribute("credential_lists", $res->getResponse());
    }

    public function testCreateCredentialsList(){
        $instance = SipCredentials::getInstance();
        $res = $instance->createCredentialsList(array(
            "FriendlyName" => "MyCredentialsList"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testUpdateCredentialsList(){
        $instance = SipCredentials::getInstance();
        $res = $instance->updateCredentialsList(array(
            "CLSid" => "TestCredentialsListSid",
            "FriendlyName" => "NewCredentialsList"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testViewCredential(){
        $instance = SipCredentials::getInstance();
        $res = $instance->viewCredential(array(
            "CLSid" => "TestCredentialsListSid",
            "CredentialSid" => "TestCredentialSid"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testDeleteCredentialsList(){
        $instance = SipCredentials::getInstance();
        $res = $instance->deleteCredentialsList(array(
            "CLSid" => "TestCredentialsListSid",
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testListCredentials(){
        $instance = SipCredentials::getInstance();
        $res = $instance->listCredentials(array(
            "CLSid" => "TestCredentialsListSid",
        ));
        $this -> assertObjectHasAttribute("credentials", $res->getResponse());
    }

    public function testCreateCredentials(){
        $instance = SipCredentials::getInstance();
        $res = $instance->createCredential(array(
            "CLSid"    => "TestCredentialsListSid",
            "Username" => "username",
            "Password" => "password"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testDeleteCredentials(){
        $instance = SipCredentials::getInstance();
        $res = $instance->deleteCredential(array(
            "CLSid" => "TestCredentialsListSid",
            "CredentialSid" => "TestCredentialSid"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testUpdateCredentials(){
        $instance = SipCredentials::getInstance();
        $res = $instance->updateCredential(array(
            "CLSid" => "TestCredentialsListSid",
            "CredentialSid" => "TestCredentialSid",
            "Password" => "password"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}