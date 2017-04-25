<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/CarrierService.php";

/**
 * @covers CarrierService
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class CarrierServiceTest extends TestCase {

    public function testCarrierLookup(){
            $instance = CarrierService::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $instance->carrierLookup(array(
                "PhoneNumber"           => "+1234"
            ));
            $this->checkResponseCarrier($res->getResponse()->carrier_lookups[0]);
    }

    public function testCarrierLookupList(){
            $instance = CarrierService::getInstance();
            $res = $instance->carrierLookupList(array(
                "Page"               => "0",
                "PageSize"           => "33"
            ));
            $this->checkResponseCarrier($res->getResponse()->carrier_lookups[0]);

    }

    private function checkResponseCarrier($response){
        $this -> assertEquals("+14086474636", $response->phone_number);
        $this -> assertEquals("CRe1889084f671bc10f6d24ee3a16998bd", $response->sid);
    }

    public function testCNAMLookup(){
            $instance = CarrierService::getInstance();
            $res = $instance->CNAMLookup(array(
                "PhoneNumber"           => "+1234"
            ));
            $this->checkResponseCNAM($res->getResponse()->cnam_dips[0]);
    }

    public function testCNAMLookupList(){
            $instance = CarrierService::getInstance();
            $res = $instance->CNAMLookupList(array(
                "Page"               => "0",
                "PageSize"           => "33"
            ));
            $this->checkResponseCNAM($res->getResponse()->cnam_dips[0]);

    }

    private function checkResponseCNAM($response)
    {
        $this->assertEquals("+19093900002", $response->phone_number);
        $this->assertEquals("CL6588908407bcc1991e244475aaadec39", $response->sid);
    }

    public function testBnaLookup(){
            $instance = CarrierService::getInstance();
            $res = $instance->BnaLookup(array(
                "PhoneNumber"           => "+1234"
            ));
            $this->checkResponseBna($res->getResponse()->bna_lookups[0]);
    }

    public function testBnaLookupList(){
            $instance = CarrierService::getInstance();
            $res = $instance->BnaLookupList(array(
                "Page"               => "0",
                "PageSize"           => "33"
            ));
            $this->checkResponseBna($res->getResponse()->bna_lookups[0]);

    }

    private function checkResponseBna($response)
    {
        $this->assertEquals("+14086474636", $response->phone_number);
        $this->assertEquals("BL2288908475cdd864351b4d048d6ebabd", $response->sid);
    }
}