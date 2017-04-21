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
require_once $applicationRoot . "/connectors/Transcriptions.php";

/**
 * @covers Transcriptions
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class TranscriptionsTest extends TestCase {

    public function testViewTranscription(){
        $instance = Transcriptions::getInstance(array(
            "account_sid" => ACCOUNT_SID,
            "auth_token" => AUTH_TOKEN
        ));
        $res = $instance->viewTranscription(array(
            "TranscriptionSid" => "TestTranscriptionSid"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testListTranscription(){
        $instance = Transcriptions::getInstance();
        $res = $instance->listTranscriptions(array(
            "Status" => "completed",
            "DateTranscribed>" => "2016-12-12",
            "DateTranscribed<" => "2017-03-19",
            "Page" => "0",
            "PageSize" => "33"
        ));
        $this -> assertObjectHasAttribute("transcriptions", $res->getResponse());
    }

    public function testTranscribeRecording(){
        $instance = Transcriptions::getInstance();
        $res = $instance->transcribeRecording(array(
            "RecordingSid" => "TestRecordingSid",
            "TranscribeCallback" => "TranscribeCallback",
            "CallbackMethod" => "GET",
            "SliceStart" => "0",
            "SliceDuration" => "33",
            "Quality" => "hybrid"
        ));
        $this -> checkResponse($res->getResponse());
    }

    public function testTranscribeAudioUrl(){
        $instance = Transcriptions::getInstance();
        $res = $instance->transcribeAudioUrl(array(
            "AudioUrl" => "AudioUrl",
            "TranscribeCallback" => "TranscribeCallback",
            "CallbackMethod" => "GET",
            "SliceStart" => "0",
            "SliceDuration" => "33",
            "Quality" => "auto"
        ));
        $this -> checkResponse($res->getResponse());
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
    }
}