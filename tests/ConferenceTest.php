<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 3.4.2017.
 * Time: 23:50
 */
use PHPUnit\Framework\TestCase;

$applicationRoot = dirname( dirname(__FILE__ ) );

require_once $applicationRoot . "/connectors/Conferences.php";

/**
 * @covers Conferences
 * @covers Zang_Connector
 * @covers Zang_Helpers
 * @covers Zang_Related
 * @covers Zang_Schemas
 * @covers ZangException
 */
final class ConferenceTest extends TestCase {

    public function testViewConference(){
            $instance = Conferences::getInstance(array(
                "account_sid" => $_ENV["ACCOUNT_SID"],
                "auth_token" => $_ENV["AUTH_TOKEN"]
            ));
            $res = $instance->viewConference(array(
                "ConferenceSid"                        => "TestConferenceSid"
            ));
            $this->checkResponse($res->getResponse());
    }

    public function testListConferences(){
            $instance = Conferences::getInstance();
            $res = $instance->listConferences(array(
                "Status"                              => "completed",
                "DateCreated>"                        => "2016-12-12",
                "DateCreated<"                        => "2017-03-19",
                "DateUpdated>"                        => "2016-12-12",
                "DateUpdated<"                        => "2017-03-19",
                "Page"                                => "0",
                "PageSize"                            => "10"
            ));
            $this->checkResponse($res->getResponse()->conferences[0]);
    }

    private function checkResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
        $this -> assertObjectHasAttribute("friendly_name", $response);
    }

    public function testViewParticipant(){
            $instance = Conferences::getInstance();
            $res = $instance->viewParticipant(array(
                "ConferenceSid"                              => "TestConferenceSid",
                "ParticipantSid"                             => "TestParticipantSid"
            ));
            $this->checkParticipantResponse($res->getResponse());
    }

    public function testlistParticipants(){
            $instance = Conferences::getInstance();
            $res = $instance->listParticipants(array(
                "ConferenceSid"                     => "TestConferenceSid",
                "Muted"                             => "false",
                "Deaf"                              => "false",
                "Page"                              => "0",
                "PageSize"                          => "10",
            ));
            $this->checkParticipantResponse($res->getResponse()->participants[0]);
    }

    public function testMuteDeafParticipant(){
            $instance = Conferences::getInstance();
            $res = $instance->muteDeafParticipant(array(
                "ConferenceSid"                     => "TestConferenceSid",
                "ParticipantSid"                    => "TestParticipantSid",
                "Muted"                             => "true",
                "Deaf"                              => "true",
            ));
            $this->checkParticipantResponse($res->getResponse());
    }

    public function testPlayAudioToParticipant(){
            $instance = Conferences::getInstance();
            $res = $instance->playAudioToParticipant(array(
                "ConferenceSid"                     => "TestConferenceSid",
                "ParticipantSid"                    => "TestParticipantSid",
                "AudioUrl"                          => "http://mydomain.com/audio.mp3",
            ));
            $this->checkParticipantResponse($res->getResponse());
    }

    public function testHangupParticipant(){
            $instance = Conferences::getInstance();
            $res = $instance->hangupParticipant(array(
                "ConferenceSid"                     => "TestConferenceSid",
                "ParticipantSid"                    => "TestParticipantSid",
            ));
            $this->checkParticipantResponse($res->getResponse());
    }

    private function checkParticipantResponse($response){
        $this -> assertObjectHasAttribute("sid", $response);
        $this -> assertObjectHasAttribute("conference_sid", $response);

    }


}