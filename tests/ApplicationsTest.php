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
require_once $applicationRoot . "/connectors/Applications.php";

/**
 * @covers Applications
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class ApplicationsTest extends TestCase {

    public function testViewApplication(){
            $instance = Applications::getInstance(array(
                "account_sid" => ACCOUNT_SID,
                "auth_token" => AUTH_TOKEN
            ));
            $res = $instance->viewApplication(array(
                "ApplicationSid"      => "TestApplicationSid",
            ));
            $this->checkResponse($res);
    }

    public function testCreateApplication(){
            $instance = Applications::getInstance();
            $res = $instance->createApplication(array(
                "FriendlyName"              => "TestApplication",
                "VoiceUrl"                  => "voiceUrl",
                "VoiceMethod"               => "POST",
                "VoiceFallbackUrl"          => "voiceFallbackUrl",
                "VoiceFallbackMethod"       => "GET",
                "VoiceCallerIdLookup"       => "true",
                "SmsUrl"                    => "smsUrl",
                "SmsMethod"                 => "POST",
                "SmsFallbackUrl"            => "smsFallbackUrl",
                "SmsFallbackMethod"         => "GET",
                "HeartbeatUrl"              => "heartbeatUrl",
                "HeartbeatMethod"           => "GET",
                "StatusCallback"            => "statusCallback",
                "StatusCallbackMethod"      => "POST",
                "HangupCallback"            => "hangupCallback",
                "HangupCallbackMethod"      => "GET",
            ));
            $this->checkResponse($res);
    }

    public function testUpdateApplication(){
            $instance = Applications::getInstance();
            $res = $instance->updateApplication(array(
                "ApplicationSid"            => "TestApplicationSid",
                "FriendlyName"              => "TestApplication",
                "VoiceUrl"                  => "voiceUrl",
                "VoiceMethod"               => "POST",
                "VoiceFallbackUrl"          => "voiceFallbackUrl",
                "VoiceFallbackMethod"       => "GET",
                "VoiceCallerIdLookup"       => "true",
                "SmsUrl"                    => "smsUrl",
                "SmsMethod"                 => "POST",
                "SmsFallbackUrl"            => "smsFallbackUrl",
                "SmsFallbackMethod"         => "GET",
                "HeartbeatUrl"              => "heartbeatUrl",
                "HeartbeatMethod"           => "GET",
                "StatusCallback"            => "statusCallback",
                "StatusCallbackMethod"      => "POST",
                "HangupCallback"            => "hangupCallback",
                "HangupCallbackMethod"      => "GET",
            ));
            $this->checkResponse($res);

    }

    public function testDeleteApplication(){
            $instance = Applications::getInstance();
            $res = $instance->deleteApplication(array(
                "ApplicationSid"              => "TestApplicationSid"
            ));
            $this->checkResponse($res);
    }

    public function testListApplication(){
            $instance = Applications::getInstance();
            $res = $instance->listApplication(array(
                "Page"              => "0",
                "PageSize"          => "10",
                "FriendlyName"      => "TestApplication"
            ));
            $this->checkResponse($res->getResponse()->applications[0]);
    }

    private function checkResponse($response){
        $this -> assertEquals("TestApplicationSid", $response->sid);
        $this -> assertEquals("TestAccountSid", $response->account_sid);
    }
}