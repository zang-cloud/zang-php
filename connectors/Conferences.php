<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 25.3.2017.
 * Time: 21:09
 */

if( floatval(phpversion()) < 5.2) {
    trigger_error(sprintf(
        "Your PHP version %s is not valid. In order to run ZangAPI helper you will need to have at least PHP 5.2 or above.",
        phpversion()
    ));
}

$appRoot = dirname( dirname(__FILE__ ) );

/** @see Application config **/
require_once $appRoot . '/configuration/applications.config.php';

/** @see Zang_Helpers **/
require_once $appRoot . '/library/ZangApi/Helpers.php';

/** @see ZangException */
require_once $appRoot . '/library/ZangApi/ZangException.php';

/** @see Zang_Schemas */
require_once $appRoot . '/library/ZangApi/Schemas.php';

/** @see Zang_InboundXML **/
require_once $appRoot . '/library/ZangApi/InboundXML.php';

/** @see Zang_Connector **/
require_once $appRoot . '/library/ZangApi/Connector.php';

/** @see Zang_Related **/
require_once $appRoot . '/library/ZangApi/Related.php';


class Conferences extends Zang_Related {

    /**
     * Singleton instance container
     * @var Conferences|null
     */
    protected static $_instance = null;

    /**
     * Singleton access method. This is THE ONLY PROPER WAY to access the wrapper!
     * @param array $options{
     *      @type string $options["account_sid"]              [Account SID - mandatory field]
     *      @type string $options["auth_token"]               [Authorisation token - mandatory field]
     *      @type boolean $options["response_to_array"]       [convert response to array; true|false]
     *      @type string $options["wrapper_type"]             [Related::WRAPPER_JSON|Related::WRAPPER_XML|Related::WRAPPER_TXT]
     *      @type string $options["api_version"]              ['v2']
     * }
     *
     * @return Conferences
     */
    static function getInstance( Array $options=array() ) {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
            if( (defined("ACCOUNT_SID") && ACCOUNT_SID != "") && (defined("AUTH_TOKEN") && AUTH_TOKEN != "") ){
                self::$_instance -> setOptions(array(
                    "account_sid"   => ACCOUNT_SID,
                    "auth_token"    => AUTH_TOKEN,
                ));
            }
        }

        if( !empty($options) ){
            self::$_instance -> setOptions($options);
        }

        return self::$_instance;
    }

    /**
     * shows information on some conference
     *  * @param array $reqData {
     *      @type $reqData['ConferenceSid'][Conference SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewConference( Array $reqData = array() ){
        if( !isset($reqData['ConferenceSid']) || is_null($reqData['ConferenceSid']) ) throw new ZangException("'ConferenceSid' is not set.");
        else {
            return self::$_instance->get(array('conferences', $reqData['ConferenceSid'] ));
        }
    }

    /**
     * shows information on all conferences associated with some account
     * @params array $reqData {
     *      @type string $reqData['FriendlyName'] [Filters conferences by the given FriendlyName.]
     *      @type string $reqData['Status'] [Filters conferences by the given status. Allowed values are "init", "in-progress", or "completed".]
     *      @type string $reqData['DateCreated'] [Filter by conferences created on, after, or before a given date. Date range can be specified using inequalities like "DateCreated>=YYYY-MM-DD". Allowed values are dates in the YYYY-MM-DD format.]
     *      @type string $reqData['DateUpdated'] [Filter by conferences updated on or after a given date. Date range can be specified using inequalities like "DateCreated>=YYYY-MM-DD". Allowed values are dates in the YYYY-MM-DD format.]
     *      @type int $reqData['Page'] [Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'] [Used to specify the amount of list items to return per page.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listConferences(Array $reqData = array() ){
        return self::$_instance->get('conferences', $reqData);
    }

    /**
     * shows info on some conference participant
     * * @params array $reqData {
     *      @type string $reqData['ConferenceSid'] [Conference SID]
     *      @type string $reqData['ParticipantSid'] [Participant SID]
     *}
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewParticipant( Array $reqData=array() ){
        if( !isset($reqData['ConferenceSid']) || is_null($reqData['ConferenceSid']) ) throw new ZangException("'ConferenceSid' is not set.");
        elseif( !isset($reqData['ParticipantSid']) || is_null($reqData['ParticipantSid']) ) throw new ZangException("'ParticipantSid' is not set.");
        else {
            return self::$_instance->get(array('conferences', $reqData['ConferenceSid'], "Participants", $reqData['ParticipantSid'] ));
        }
    }

    /**
     * options include filtering by muted or deaf
     * @params array $reqData {
     *      @type string $reqData['ConferenceSid'] [Conference SID]
     *      @type boolean $reqData['Muted'] [false|Filter by participants that are muted. Allowed values are "true" or "false".]
     *      @type boolean $reqData['Deaf'] [false|Filter by participants that are deaf. Allowed values are "true" or "false".]
     *      @type int $reqData['Page'] [Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'] [Used to specify the amount of list items to return per page.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listParticipants( Array $reqData=array() ){
        if( !isset($reqData['ConferenceSid']) || is_null($reqData['ConferenceSid']) ) throw new ZangException("'ConferenceSid' is not set.");
        else {
            $sid = $reqData['ConferenceSid'];
            unset($reqData['ConferenceSid']);
            return self::$_instance->get(array('conferences', $sid, "Participants"), $reqData);
        }
    }

    /**
     * sets participant in conference to mute or deaf

     * @params array $reqData {
     *      @type string $reqData['ConferenceSid'] [Conference SID]
     *      @type string $reqData['ParticipantSid'] [Participant SID]
     *      @type boolean $reqData['Muted'] [false|Filter by participants that are muted. Allowed values are "true" or "false".]
     *      @type boolean $reqData['Deaf'] [false|Filter by participants that are deaf. Allowed values are "true" or "false".]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function muteDeafParticipant( Array $reqData=array() ){
        if( !isset($reqData['ConferenceSid']) || is_null($reqData['ConferenceSid']) ) throw new ZangException("'ConferenceSid' is not set.");
        elseif( !isset($reqData['ParticipantSid']) || is_null($reqData['ParticipantSid']) ) throw new ZangException("'ParticipantSid' is not set.");
        else {
            $confsid = $reqData['ConferenceSid'];
            unset($reqData['ConferenceSid']);
            $participantsid = $reqData['ParticipantSid'];
            unset($reqData['ParticipantSid']);
            return self::$_instance->update(array('conferences', $confsid, "Participants", $participantsid), $reqData);
        }
    }

    /**
     * plays an audio file to a conference participant
     * @params array $reqData {
     *      @type string $reqData['ConferenceSid'] [Conference SID]
     *      @type string $reqData['ParticipantSid'] [Participant SID]
     *      @type string $reqData['AudioUrl'] [A URL to the audio file that will be played. Mutliple AudioUrl parameters may be passed to play more than one file.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function playAudioToParticipant(  Array $reqData=array() ){
        if( !isset($reqData['ConferenceSid']) || is_null($reqData['ConferenceSid']) ) throw new ZangException("'ConferenceSid' is not set.");
        elseif( !isset($reqData['ParticipantSid']) || is_null($reqData['ParticipantSid']) ) throw new ZangException("'ParticipantSid' is not set.");
        else {
            $confsid = $reqData['ConferenceSid'];
            unset($reqData['ConferenceSid']);
            $participantsid = $reqData['ParticipantSid'];
            unset($reqData['ParticipantSid']);
            return self::$_instance->update(array('conferences', $confsid, "Participants", $participantsid, "Play"), $reqData);
        }
    }

    /**
     * hangs up a conference participant
     * @params array $reqData {
     *      @type string $reqData['ConferenceSid'] [Conference SID]
     *      @type string $reqData['ParticipantSid'] [Participant SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function hangupParticipant( Array $reqData = array() ){
        if( !isset($reqData['ConferenceSid']) || is_null($reqData['ConferenceSid']) ) throw new ZangException("'ConferenceSid' is not set.");
        else {
            $pathData = null;
            if(!isset($reqData["ParticipantSid"])){
                $pathData = array('conferences', $reqData["ConferenceSid"]);
            } else {
                $pathData = array('conferences', $reqData["ConferenceSid"], "Participants", $reqData["ParticipantSid"]);
            }

            return self::$_instance->delete($pathData);
        }
    }

}