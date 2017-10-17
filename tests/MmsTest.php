<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/Mms.php";

/**
 * @covers Sms
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class MmsTest extends TestCase {

    public function testSendMms()
    {
            $mmsInstance = mms::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));

            $res = $mmsInstance->sendMms(array(
                'From'          => "+654321",
                'To'            => "+123456",
                'Body'          => 'This is MMS sent from Zang',
                'MediaUrl'      => 'https://media.giphy.com/media/zZJzLrxmx5ZFS/giphy.gif'
            ));
            $this->checkResponse($res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("body", $response);
        $this -> assertObjectHasAttribute("status", $response);
        $this -> assertObjectHasAttribute("direction", $response);
        $this -> assertObjectHasAttribute("from", $response);
        $this -> assertObjectHasAttribute("account_sid", $response);
        $this -> assertObjectHasAttribute("to", $response);
        $this -> assertObjectHasAttribute("mms_sid", $response);
        $this -> assertObjectHasAttribute("date_created", $response);
        $this -> assertObjectHasAttribute("apiVersion", $response);
        $this -> assertObjectHasAttribute("media_url", $response);
    }
}