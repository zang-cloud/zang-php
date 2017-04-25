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

/** @see ZangException */
require_once $appRoot . '/library/ZangApi/ZangException.php';

/** @see Application config **/
require_once $appRoot . '/library/configHelper.php';

/** @see Zang_Helpers **/
require_once $appRoot . '/library/ZangApi/Helpers.php';

/** @see Zang_Schemas */
require_once $appRoot . '/library/ZangApi/Schemas.php';

/** @see Zang_InboundXML **/
require_once $appRoot . '/library/ZangApi/InboundXML.php';

/** @see Zang_Connector **/
require_once $appRoot . '/library/ZangApi/Connector.php';

/** @see Zang_Related **/
require_once $appRoot . '/library/ZangApi/Related.php';



class Recordings extends Zang_Related {

    /**
     * Singleton instance container
     * @var Recordings|null
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
     * @return Recordings
     */
    static function getInstance( Array $options=array() ) {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
            if( (isset($_ENV['ACCOUNT_SID']) && $_ENV['ACCOUNT_SID'] != "") && (isset($_ENV["AUTH_TOKEN"]) && $_ENV["AUTH_TOKEN"] != "") ){
                self::$_instance -> setOptions(array(
                    "account_sid"   => $_ENV['ACCOUNT_SID'],
                    "auth_token"    => $_ENV["AUTH_TOKEN"],
                ));
            }
        }

        if( !empty($options) ){
            self::$_instance -> setOptions($options);
        }

        return self::$_instance;
    }

    /**
     * shows info on all recordings associated with some account
     * @param $reqData array {
     *      @type string $reqData['CallSid'][Filters by recordings associated with a given CallSid.]
     *      @type string $reqData['DateCreated'][Lists all recordings created on or after a certain date. Date range can be specified using inequalities like so: DateSent>=YYYY-MM-DD.]
     *      @type int $reqData['Page'][1|Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'][50|Used to specify the amount of list items to return per page.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listRecordings( Array $reqData=array() ){
        return self::$_instance->get('recordings', $reqData);
    }

    /**
     * deletes a recording
     * @param $reqData array {
     *      @type string $reqData['RecordingSid'][Recording SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteRecording( Array $reqData=array() ){
        if( !isset($reqData['RecordingSid']) || is_null( $reqData['RecordingSid'] )) throw new ZangException("'RecordingSid' is not set.");
        else {
            return self::$_instance->delete(array('recordings', $reqData['RecordingSid']));
        }
    }

    /**
     * records a call
     * @param $reqData array {
     *      @type string $reqData['CallSid'][Call SID]
     *      @type string $reqData['Record'][Specifies if a call recording should start or end. Allowed values are "true" to start recording and "false" to end recording. Any number of simultaneous, separate recordings can be initiated.]
     *      @type string $reqData['Direction'][both|Specifies which audio stream to record. Allowed values are "in" to record the incoming caller's audio, "out" to record the outgoing caller's audio, and "both" to record both.]
     *      @type string $reqData['TimeLimit'][The maximum duration of the recording. Allowed value is an integer greater than 0.]
     *      @type string $reqData['CallbackUrl'][A URL that will be requested when the recording ends, sending information about the recording. The longer the recording, the longer the delay in processing the recording and requesting the CallbackUrl. Url length is limited to 200 characters.]
     *      @type string $reqData['FileFormat'][mp3|Specifies the file format of the recording. Allowed values are "mp3" or "wav" - any other value will default to "mp3".]
     *      @type string $reqData['TrimSilence'][false|Trims all silence from the beginning of the recording. Allowed values are "true" or "false" - any other value will default to "false".]
     *      @type string $reqData['Transcribe'][false|Specifies if this recording should be transcribed. Allowed values are "true" and "false" - all other values will default to "false".]
     *      @type string $reqData['TranscribeQuality'][auto|Specifies the quality of the transcription. Allowed values are "auto" for automated transcriptions and "hybrid" for human-reviewed transcriptions - all other values will default to "auto".]
     *      @type string $reqData['TranscribeCallback'][A URL that will be requested when the call ends, sending information about the transcription. The longer the recording, the longer the delay in processing the transcription and requesting the TranscribeCallback. URL length is limited to 200 characters.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function recordCall( Array $reqData=array() ){
        if( !isset($reqData['CallSid']) || is_null( $reqData['CallSid'] )) throw new ZangException("'CallSid' is not set.");
        elseif( !isset($reqData['Record']) || is_null( $reqData['Record'] )) throw new ZangException("'Record' is not set.");
        else {
            $sid = $reqData['CallSid'];
            unset($reqData['CallSid']);
            return self::$_instance->create(array('calls', $sid, "Recordings"), $reqData);
        }
    }

    /**
     * shows information on some recording
     * @param $reqData array {
     *      @type string $reqData['RecordingSid'][Recording SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewRecording( Array $reqData=array() ){
        if( !isset($reqData['RecordingSid']) || is_null( $reqData['RecordingSid'] )) throw new ZangException("'RecordingSid' is not set.");
        else {
            return self::$_instance->get(array('recordings', $reqData['RecordingSid']));
        }
    }
}