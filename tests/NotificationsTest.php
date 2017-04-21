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
require_once $applicationRoot . "/connectors/Notifications.php";

/**
 * @covers Notifications
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class NotificationsTest extends TestCase {

    public function testListNotification(){
        $instance = Notifications::getInstance(array(
            "account_sid" => ACCOUNT_SID,
            "auth_token" => AUTH_TOKEN
        ));
        $res = $instance->listNotifications(array(
            "Log" => "2",
            "Page" => "0",
            "PageSize" => "33",
        ));
        $this -> assertObjectHasAttribute("notifications", $res->getResponse());
    }

    public function testViewNotification(){
        $instance = Notifications::getInstance();
        $res = $instance->viewNotification(array(
            "NotificationSid" => "TestNotificationSid"
        ));
        $this -> checkResponse($res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}