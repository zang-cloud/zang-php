<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/configuration/applications.config.php";
require_once $applicationRoot . "/connectors/SipDomains.php";

/**
 * @covers SipDomains
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class SipDomainsTest extends TestCase {

    public function testViewDomain(){
        $instance = SipDomains::getInstance(array(
            "account_sid" => ACCOUNT_SID,
            "auth_token" => AUTH_TOKEN
        ));
        $res = $instance->viewDomain(array(
            "DomainSid" => "TestDomainSid"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testListDomains(){
        $instance = SipDomains::getInstance();
        $res = $instance->listDomains();
        $this -> assertObjectHasAttribute("domains", $res->getResponse());
    }

    public function testCreateDomains(){
        $instance = SipDomains::getInstance();
        $res = $instance->createDomain(array(
            "DomainName" => "mydomain.com",
            "FriendlyName" => "MyDomain",
            "VoiceUrl" => "VoiceUrl",
            "VoiceMethod" => "POST",
            "VoiceFallbackUrl" => "VoiceFallbackUrl",
            "VoiceFallbackMethod" => "GET",
            "HeartbeatUrl" => "HeartbeatUrl",
            "HeartbeatMethod" => "POST",
            "VoiceStatusCallback" => "VoiceStatusCallback",
            "VoiceStatusCallbackMethod" => "GET"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testUpdateDomains(){
        $instance = SipDomains::getInstance();
        $res = $instance->updateDomain(array(
            "DomainSid" => "TestDomainSid",
            "FriendlyName" => "MyDomain",
            "VoiceUrl" => "VoiceUrl",
            "VoiceMethod" => "POST",
            "VoiceFallbackUrl" => "VoiceFallbackUrl",
            "VoiceFallbackMethod" => "GET",
            "HeartbeatUrl" => "HeartbeatUrl",
            "HeartbeatMethod" => "POST",
            "VoiceStatusCallback" => "VoiceStatusCallback",
            "VoiceStatusCallbackMethod" => "GET"
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testDeleteDomains(){
        $instance = SipDomains::getInstance();
        $res = $instance->deleteDomain(array(
            "DomainSid" => "TestDomainSid",
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testListMappedCredentialList(){
        $instance = SipDomains::getInstance();
        $res = $instance->listMappedCredentialList(array(
            "DomainSid" => "TestDomainSid",
        ));
        $this -> assertObjectHasAttribute("credential_lists", $res->getResponse());
    }

    public function testMapCredentialList(){
        $instance = SipDomains::getInstance();
        $res = $instance->mapCredentialList(array(
            "DomainSid" => "TestDomainSid",
            "CredentialListSid" => "TestCredentialsListSid",
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testDeleteMappedCredentialList(){
        $instance = SipDomains::getInstance();
        $res = $instance->deleteMappedCredentialList(array(
            "DomainSid" => "TestDomainSid",
            "CLSid" => "TestCredentialsListSid",
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testListMappedIpAcls(){
        $instance = SipDomains::getInstance();
        $res = $instance->listMappedIpAcls(array(
            "DomainSid" => "TestDomainSid",
        ));
        $this -> assertObjectHasAttribute("ip_access_control", $res->getResponse());
    }

    public function testMapIpAcl(){
        $instance = SipDomains::getInstance();
        $res = $instance->mapIpAcl(array(
            "DomainSid" => "TestDomainSid",
            "IpAccessControlListSid" => "TestIpAccessControlListSid",
        ));
        $this -> checkResponse( $res->getResponse());
    }

    public function testDeleteMappedIpAcl(){
        $instance = SipDomains::getInstance();
        $res = $instance->deleteMappedIpAcl(array(
            "DomainSid" => "TestDomainSid",
            "ALSid" => "TestIpAccessControlListSid",
        ));
        $this -> checkResponse( $res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}