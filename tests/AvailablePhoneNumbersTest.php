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
require_once $applicationRoot . "/connectors/AvailablePhoneNumbers.php";

/**
 * @covers AvailablePhoneNumbers
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class AvailablePhoneNumbersTest extends TestCase {

    public function testViewApplication(){
            $instance = AvailablePhoneNumbers::getInstance(array(
                "account_sid" => ACCOUNT_SID,
                "auth_token" => AUTH_TOKEN
            ));
            $res = $instance->listAvailableNumbers(array(
                "Country"           => "HR",
                "Type"              => "Tollfree",
                "Contains"          => "123",
                "AreaCode"          => "052",
                "InRegion"          => "Istria",
                "InPostalCode"      => "52210",
                "Page"              => "0",
                "PageSize"          => "20"
            ));
            $this->checkResponse($res);
    }

    private function checkResponse($response){
        $this -> assertEquals("+38551770239", $response->getResponse()->available_phone_numbers[0]->phone_number);
        $this -> assertEquals("+38521770005", $response->getResponse()->available_phone_numbers[1]->phone_number);
    }
}