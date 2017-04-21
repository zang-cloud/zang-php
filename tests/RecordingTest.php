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
require_once $applicationRoot . "/connectors/Recordings.php";

/**
 * @covers Recordings
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class RecordingTest extends TestCase {

    public function testListRecordings(){
        $instance = Recordings::getInstance(array(
            "account_sid" => ACCOUNT_SID,
            "auth_token" => AUTH_TOKEN
        ));
        $res = $instance->listRecordings(array(
            "CallSid" => "TestCallSid",
            "DateCreated>" => "2016-12-12",
            "DateCreated<" => "2017-03-19",
            "Page" => "0",
            "PageSize" => "33",
        ));
        $this -> assertObjectHasAttribute("recordings", $res->getResponse());
    }

    public function testDeleteRecording(){
        $instance = Recordings::getInstance();
        $res = $instance->deleteRecording(array(
            "RecordingSid" => "TestRecordingSid"
        ));
        $this -> checkResponse( $res->getResponse() );
    }

    public function testRecordCall(){
        $instance = Recordings::getInstance();
        $res = $instance->recordCall(array(
            "CallSid" => "TestCallSid",
            "" => "",
            "Record" => "true",
            "Direction" => "out",
            "TimeLimit" => "1337",
            "CallbackUrl" => "CallbackUrl",
            "FileFormat" => "wav",
            "TrimSilence" => "true",
            "Transcribe" => "true",
            "TranscribeQuality" => "hybrid",
            "TranscribeCallback" => "TranscribeCallback",
        ));
        $this -> checkResponse( $res->getResponse() );
    }

    public function testViewRecord(){
        $instance = Recordings::getInstance();
        $res = $instance->viewRecording(array(
            "RecordingSid" => "TestRecordingSid"
        ));
        $this -> checkResponse( $res->getResponse() );
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}