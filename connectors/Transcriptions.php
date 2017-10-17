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



class Transcriptions extends Zang_Related {

    /**
     * Singleton instance container
     * @var Transcriptions|null
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
     * @return Transcriptions
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
     * shows info on some transcription
     * @param $reqData array {
     *      @type string $reqData['TranscriptionSid'][Transcription SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewTranscription( Array $reqData=array() ){
        if( !isset($reqData['TranscriptionSid']) || is_null( $reqData['TranscriptionSid'] )) throw new ZangException("'TranscriptionSid' is not set.");
        else {
            return self::$_instance->get(array('transcriptions', $reqData['TranscriptionSid']));
        }
    }

    /**
     * shows info on all transcriptions associated with some account
     * @param $reqData array {
     *      @type string $reqData['Status'][Filter by transcriptions with a given status. Allowed values are "completed", "in-progress", and "failed"..]
     *      @type string $reqData['DateTranscribed'][Lists all transcriptions created on or after a certain date. Date range can be specified using inequalities like so: "DateSent>=YYYY-MM-DD"..]
     *      @type int $reqData['Page'][Used to return a particular page within the list..]
     *      @type int $reqData['PageSize'][Used to specify the amount of list items to return per page..]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listTranscriptions( Array $reqData=array() ){
        return self::$_instance->get('transcriptions', $reqData);
    }

    /**
     * transcribes some recording
     * @param $reqData array {
     *      @type string $reqData['RecordingSid'][Recording SID]
     *      @type string $reqData['TranscribeCallback'][The URL some parameters regarding the transcription will be passed to once it is completed. The longer the recording time, the longer the process delay in returning the transcription information. If no TranscribeCallback is given, the recording will still be saved to the system and available either in your Transcriptions Logs or via a REST List Transcriptions (ADD URL LINK) request. URL length is limited to 200 characters.]
     *      @type string $reqData['CallbackMethod'][POST|The HTTP method used to request the TranscribeCallback. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['SliceStart'][Start point for slice transcription (in seconds).]
     *      @type string $reqData['SliceDuration'][Duration of slice transcription (in seconds).]
     *      @type string $reqData['Quality'][auto|Specifies the transcription quality. Transcription price differs for each quality tier. See pricing page for details. Allowed values are "auto", "hybrid" and "keywords", where "auto" is a machine-generated transcription, "hybrid" is reviewed by a human for accuracy and "keywords" returns topics and keywords for given audio file.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function transcribeRecording( Array $reqData=array() ){
        if( !isset($reqData['RecordingSid']) || is_null( $reqData['RecordingSid'] )) throw new ZangException("'RecordingSid' is not set.");
        else {
            $sid = $reqData['RecordingSid'];
            unset($reqData['RecordingSid']);
            return self::$_instance->create(array('recordings', $sid, "Transcriptions"), $reqData);
        }
    }

    /**
     * transcribes an audio file on some URL
     * @param $reqData array {
     *      @type string $reqData['AudioUrl'][URL where the audio to be transcribed is located.]
     *      @type string $reqData['TranscribeCallback'][URL that will be requested when the transcription has finished processing.]
     *      @type string $reqData['SliceStart'][Start point for slice transcription (in seconds).]
     *      @type string $reqData['CallbackMethod'][Specifies the HTTP method to use when requesting the TranscribeCallback URL. Allowed values are "POST" and "GET".]
     *      @type string $reqData['SliceDuration'][Duration of slice transcription (in seconds).]
     *      @type string $reqData['Quality'][auto|Specifies the transcription quality. Transcription price differs for each quality tier. See pricing page for details. Allowed values are "auto", "hybrid" and "keywords", where "auto" is a machine-generated transcription, "hybrid" is reviewed by a human for accuracy and "keywords" returns topics and keywords for given audio file.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function transcribeAudioUrl( Array $reqData=array() ){
        return self::$_instance->create("transcriptions", $reqData);
    }
}