<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/Call.php";

/**
 * @covers Call
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class CallTest extends TestCase {

    public function testMakeCall(){
            $instance = Call::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $instance->makeCall(array(
                "To"                        => "+123456",
                "From"                      => "+654321",
                "Url"                       => "TestUrl",
                "Method"                    => "GET",
                "FallbackUrl"               => "FallbackUrl",
                "FallbackMethod"            => "POST",
                "StatusCallback"            => "StatusCallback",
                "StatusCallbackMethod"      => "GET",
                "HeartbeatUrl"              => "HeartbeatUrl",
                "HeartbeatMethod"           => "GET",
                "ForwardedFrom"             => "1234",
                "PlayDtmf"                  => "123#",
                "Timeout"                   => "122",
                "HideCallerId"              => "true",
                "Record"                    => "true",
                "RecordCallback"            => "RecordCallback",
                "RecordCallbackMethod"      => "GET",
                "Transcribe"                => "true",
                "TranscribeCallback"        => "TranscribeCallback",
                "StraightToVoicemail"       => "true",
                "IfMachine"                 => "redirect",
                "IfMachineUrl"              => "IfMachineUrl",
                "IfMachineMethod"           => "GET",
                "SipAuthUsername"           => "username",
                "SipAuthPassword"           => "password",
            ));
            $this->checkResponse($res);

    }

    public function testViewCall(){
            $instance = Call::getInstance();
            $res = $instance->viewCall(array(
                "CallSid"                        => "TestCallSid"
            ));
            $this->checkResponse($res);

    }

    public function testListCalls(){
            $instance = Call::getInstance();
            $res = $instance->listCalls(array(
                "To"                         => '%2B123456',
                "From"                       => '%2B654321',
                "Status"                     => "completed",
                "StartTime>"                 => "2016-12-12",
                "StartTime<"                 => "2017-03-19",
                "Page"                       => "0",
                "PageSize"                   => "10"
            ));
            $this->checkResponse($res->getResponse()->calls[0]);

    }


    public function testInterruptLiveCall(){
            $instance = Call::getInstance();
            $res = $instance->interruptLiveCall(array(
                "CallSid"                       => "TestCallSid",
                "Url"                           => "TestUrl",
                "Method"                        => "GET",
                "Status"                        => "canceled"
            ));
            $this->checkResponse($res);

    }

    public function testSendDigitsToLiveCall(){
            $instance = Call::getInstance();
            $res = $instance->sendDigitsToLiveCall(array(
                "CallSid"                       => "TestCallSid",
                "PlayDtmf"                      => "0123#",
                "PlayDtmfDirection"             => "out"
            ));
            $this->checkResponse($res);
    }

    public function testRecordLiveCall(){
            $instance = Call::getInstance();
            $res = $instance->recordLiveCall(array(
                "CallSid"               => "TestCallSid",
                "Record"                => "true",
                "Direction"             => "both",
                "TimeLimit"             => "15",
                "CallbackUrl"           => "TestUrl",
                "FileFormat"            => "mp3",
                "TrimSilence"           => "true",
                "Transcribe"            => "true",
                "TranscribeQuality"     => "hybrid",
                "TranscribeCallback"    => "TestTranscribeUrl"
            ));
            $this->checkResponse($res);
    }

    public function testPlayAudioToLiveCall(){
            $instance = Call::getInstance();
            $res = $instance->playAudioToLiveCall(array(
                "CallSid"               => "TestCallSid",
                "AudioUrl"              => "AudioUrl",
                "Direction"             => "both",
                "Loop"                  => "true"
            ));
            $this->checkResponse($res);

    }

    public function testApplyVoiceEffect(){
            $instance = Call::getInstance();
            $res = $instance->applyVoiceEffect(array(
                "CallSid"               => "TestCallSid",
                "AudioDirection"        => "out",
                "Pitch"                 => "5",
                "PitchSemiTones"        => "4",
                "PitchOctaves"          => "3",
                "Rate"                  => "2",
                "Tempo"                 => "1",
            ));
            $this->checkResponse($res);
    }

    private function checkResponse($response){
        $this -> assertEquals("TestCallSid", $response->sid);
        $this -> assertEquals("+123456", $response->to);
    }
}