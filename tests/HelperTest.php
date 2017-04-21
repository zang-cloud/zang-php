<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/library/ZangApi/Helpers.php";

/**
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class HelperTest extends TestCase {

    public function testFilterE164(){
        $this -> assertEquals("+19995550123", Zang_Helpers::filter_e164("+19995550123"));
    }

    public function testFilterNorthAmericanNumber(){
        $this -> assertEquals("+12342355678", Zang_Helpers::filter_e164("1-234-235-5678"));
    }

    public function testFilterNorthAmericanNumberWithoutCountryCode(){
        $this -> assertEquals("+12342355678", Zang_Helpers::filter_e164("234-235-5678"));
    }

    public function testFilterInternationalNumber(){
        $this -> assertEquals("+6434774000", Zang_Helpers::filter_e164("011 64 3 477 4000"));
    }

    public function testFilterInternationalNumberWithout011Prefix(){
        $this -> assertEquals("+14155552671", Zang_Helpers::filter_e164("415 555 2671"));
    }
}