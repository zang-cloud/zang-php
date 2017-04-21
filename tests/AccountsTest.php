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
require_once $applicationRoot . "/connectors/Accounts.php";

/**
 * @covers Accounts
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class AccountsTest extends TestCase {

    public function testViewAccount(){
            $instance = Accounts::getInstance(array(
                "account_sid" => ACCOUNT_SID,
                "auth_token" => AUTH_TOKEN
            ));
            $res = $instance->viewAccount();
            $this->checkResponse($res->getResponse());
    }

    public function testUpdateAccount()
    {
            $instance = Accounts::getInstance();
            $res = $instance->updateAccount(array(
                'FriendlyName' => "friendlyname1",
            ));
            $this->checkResponse($res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("friendly_name", $response);
    }
}