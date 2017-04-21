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
require_once $applicationRoot . "/connectors/SipIpAccessControlList.php";

/**
 * @covers SipIpAccessControlList
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class SipIpAccessControlListTest extends TestCase {

    public function testViewIPACL(){
        $instance = SipIpAccessControlList::getInstance(array(
            "account_sid" => ACCOUNT_SID,
            "auth_token" => AUTH_TOKEN
        ));
        $res = $instance->viewIPACL(array(
            "IpAccessControlListSid" => "TestIpAccessControlListSid"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testListIPACLs(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->listIPACLs(array(
            "Page" => "0",
            "PageSize" => "50"
        ));
        $this -> assertObjectHasAttribute("ip_access_control", $res->getResponse());
    }

    public function testCreateIPACL(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->createIPACL(array(
            "FriendlyName" => "MyIpAclList"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testUpdateIPACL(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->updateIPACL(array(
            "IpAccessControlListSid" => "TestIpAccessControlListSid",
            "FriendlyName" => "NewIpAclList"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testDeleteIPACL(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->deleteIPACL(array(
            "IpAccessControlListSid" => "TestIpAccessControlListSid",
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testViewACLIP(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->viewACLIP(array(
            "AclSid" => "TestIpAccessControlListSid",
            "IpSid" => "TestIpAddressSid"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testListACLIP(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->listACLIP(array(
            "AclSid" => "TestIpAccessControlListSid",
        ));
        $this -> assertObjectHasAttribute("ip_addresses", $res->getResponse());
    }

    public function testAddACLIP(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->addACLIP(array(
            "AclSid" => "TestIpAccessControlListSid",
            "FriendlyName" => "MyIpAddress",
            "IpAddress" => "10.0.0.1"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testUpdateACLIP(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->updateACLIP(array(
            "AclSid" => "TestIpAccessControlListSid",
            "IpSid" => "TestIpAddressSid",
            "FriendlyName" => "NewIpAddress",
            "IpAddress" => "10.0.0.2"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testDeleteACLIP(){
        $instance = SipIpAccessControlList::getInstance();
        $res = $instance->deleteACLIP(array(
            "AclSid" => "TestIpAccessControlListSid",
            "IpSid" => "TestIpAddressSid"
        ));
        $this -> checkResponse($res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}