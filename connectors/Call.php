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



class Call extends Zang_Related {

    /**
     * Singleton instance container
     * @var Call|null
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
     * @return Call
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
     * Make Call
     * @param array $reqData {
     *      @type string  $reqData['To'] [The phone number or SIP endpoint to call. Phone number should be in international format and one recipient per request. For e.g, to dial a number in the US, the To should be, +17325551212. SIP endpoints must be prefixed with sip: e.g sip:12345@sip.zang.io.]
     *      @type string  $reqData['From'] [The number to display as calling (i.e. Caller ID). The value does not have to be a real phone number or even in a valid format. For example, 8143 could be passed to the From parameter and would be displayed as the caller ID. Spoofed calls carry an additional charge.]
     *      @type string  $reqData['Url'] [The URL requested once the call connects. This URL must be valid and should return InboundXML containing instructions on how to process your call. A badly formatted URL will NOT fallback to the FallbackUrl but return an error without placing the call. URL length is limited to 200 characters.]
     *      @type string  $reqData['Method'] [POST|The HTTP method used to request the URL once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string  $reqData['FallbackUrl'] [URL used if the required URL is unavailable or if any errors occur during execution of the InboundXML returned by the required URL. Url length is limited to 200 characters.]
     *      @type string  $reqData['FallbackMethod'] [POST|The HTTP method used to request the FallbackUrl once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string  $reqData['StatusCallback'] [A URL that will be requested when the call connects and ends, sending information about the call. URL length is limited to 200 characters.]
     *      @type string  $reqData['StatusCallbackMethod'] [POST|The HTTP method used to request the StatusCallback URL. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string  $reqData['HeartbeatUrl:'] [A URL that will be requested every 60 seconds during the call, sending information about the call. The HeartbeatUrl will NOT be requested unless at least 60 seconds of call time have elapsed. URL length is limited to 200 characters.]
     *      @type string  $reqData['HeartbeatMethod'] [POST|The HTTP method used to request the HeartbeatUrl. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string  $reqData['ForwardedFrom'] [Specifies the Forwarding From number to pass to the carrier.]
     *      @type string  $reqData['PlayDtmf'] [Dial digits or play tones using DTMF as soon as the call connects. Useful for navigating IVRs. Allowed values for digits are 0-9, #, *, W, or w (W and w are for .5 second pauses), for example 142##* (spaces are valid). Tones follow the @1000 syntax, for example to play the tone 4 for two seconds, 4@2000 (milliseconds) would be used.]
     *      @type string  $reqData['Timeout'] [60|Number of seconds call stays on line while waiting for an answer. The max time limit is 999.]
     *      @type boolean  $reqData['HideCallerId'] [false|Specifies if the Caller ID will be blocked. Allowed positive values are "true" and "True" - any other value will default to "false".]
     *      @type boolean  $reqData['Record'] [false|Specifies if this call should be recorded. Allowed positive values are "true", "True" and "1" - any other value will default to "false". Please note that no more than 5 recordings may be associated with a single call.]
     *      @type string  $reqData['RecordCallback'] [he URL some parameters regarding the recording will be passed to once it is completed. The longer the recording time, the longer the process delay in returning the recording information. If no RecordCallback is given, the recording will still be saved to the system and available either in your Recording Logs or via a REST List Recordings request. Url length is limited to 200 characters.]
     *      @type string  $reqData['RecordCallbackMethod'] [POST|The HTTP method used to request the RecordCallback. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type boolean  $reqData['Transcribe'] [false|Specifies whether this call should be transcribed. Allowed positive values are "true", "True", and "1".]
     *      @type string  $reqData['TranscribeCallback'] [POST|The URL some parameters regarding the transcription will be passed to once it is completed. The longer the recording time, the longer the process delay in returning the transcription information. If no TranscribeCallback is given, the recording will still be saved to the system and available either in your Transcriptions Logs or via a REST List Transcriptions (ADD URL LINK) request. Url length is limited to 200 characters.]
     *      @type boolean  $reqData['StraightToVoicemail'] [false|Specifies whether this call should be sent straight to the user's voicemail. Allowed positive values are "true" and "True" - any other value will default to "false".]
     *      @type string  $reqData['IfMachine'] [continue|Specifies how Zang should handle this call if it goes to voicemail. Allowed values are "continue" to proceed as normal, "redirect" to redirect the call to the ifMachineUrl, or "hangup" to hang up the call. Hangup occurs when the tone is played. IfMachine accuracy is around 90% and may not work in all countries.]
     *      @type string  $reqData['IfMachineUrl'] [The URL Zang will redirect to for instructions if a voicemail machine is detected while the IfMachine parameter is set to "redirect". Url length is limited to 200 characters.]
     *      @type string  $reqData['IfMachineMethod'] [POST|The HTTP method used to request the IfMachineUrl. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string  $reqData['FallbackMethod'] [Your authenticated SIP username, used only for SIP calls.]
     *      @type string  $reqData['FallbackMethod'] [Your authenticated SIP password, used only for SIP calls.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function makeCall ( Array $reqData = array() ){
        if( !isset($reqData['To']) || is_null( $reqData['To'] )) throw new ZangException("'To' is not set.");
        elseif( !isset($reqData['From']) || is_null( $reqData['From'] )) throw new ZangException("'From' is not set.");
        elseif( !isset($reqData['Url']) || is_null( $reqData['Url'] )) throw new ZangException("'Url' is not set.");
        else {
            return self::$_instance->create('calls', $reqData);
        }
    }

    /**
     * view all information about a call
     * @params array $reqData {
     *      @type string $reqData['CallSid'] [Filter by a specific number calls were made to.]
     *}
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewCall(  Array $reqData = array() ){
        if( !isset($reqData['CallSid']) || is_null($reqData['CallSid']) ) throw new ZangException("'CallSid' is not set.");
        else{
            return self::$_instance->get(array( 'calls', $reqData['CallSid'] ));
        }

    }

    /**
     * list all calls associated with your account or filter results
     * @params array $reqData {
     *      @type string $reqData['To'] [Filter by a specific number calls were made to.]
     *      @type string $reqData['From'] [Filter by a specific number calls were made from.]
     *      @type string $reqData['Status'] [Filter by calls with the specified status. Allowed values are "ringing", "in-progress", "queued", "busy", "completed", "no-answer", and "failed".]
     *      @type string $reqData['StartTime'] [Filter by all calls beginning on or from a certain date. Date range can be specified using inequalities like so: "StartTime>=YYYY-MM-DD".]
     *      @type int $reqData['Page'] [Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'] [Used to specify the amount of list items to return per page.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listCalls(Array $reqData = array() ){
        return self::$_instance->get('calls', $reqData);
    }

    /**
     * send new instructions to the call
     * @param array $reqData {
     *      @type $reqData['CallSid'][Call SID.]
     *      @type $reqData['Url'][The URL that in-progress calls will request for new instructions.]
     *      @type $reqData['Method'][POST|The HTTP method used to request the redirect URL. Valid parameters are GET and POST.]
     *      @type $reqData['Status'][The status used to end the call. Allowed values are "canceled" for ending queued or ringing calls, and "completed" to end in-progress calls in addition to queued and ringing calls.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function interruptLiveCall( Array $reqData = array() ){
        if( !isset($reqData['CallSid']) || is_null($reqData['CallSid']) ) throw new ZangException("'CallSid' is not set.");
        else {
            $sid = $reqData['CallSid'];
            unset($reqData['CallSid']);
            return self::$_instance->create(array( "calls", $sid ), $reqData );
        }
    }

    /**
     * use DTMF tones to mimic button presses
     * @param array $reqData {
     *      @type $reqData['CallSid'][Call SID.]
     *      @type $reqData['PlayDtmf'][Allowed values are the digits 0-9, #, *, W, or w. "w" and "W"stand for 1/2 second pauses. You can combine these values together, for example, "12ww34". Tones are also supported and follow the @1000 syntax, for example to play the tone 4 for two seconds, 4@2000 (milliseconds) would be used.]
     *      @type $reqData['PlayDtmfDirection'][Specifies which leg of the call DTMF tones will be played on. Allowed values are “in” to send tones to the incoming caller or “out” to send tones to the out going caller.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function sendDigitsToLiveCall( Array $reqData = array() ){
        if( !isset($reqData['CallSid']) || is_null($reqData['CallSid']) ) throw new ZangException("'CallSid' is not set.");
        else {
            $sid = $reqData['CallSid'];
            unset($reqData['CallSid']);
            return self::$_instance->create(array("calls", $sid), $reqData);
        }
    }

    /**
     * options include time limit, file format, trimming silence and transcribing
     * @param array $reqData {
     *      @type $reqData['CallSid'][Call SID.]
     *      @type $reqData['Record'][Specifies if a call recording should start or end. Allowed values are "true" to start recording and "false" to end recording. Any number of simultaneous, separate recordings can be initiated.]
     *      @type $reqData['Direction'][both|Specifies which audio stream to record. Allowed values are "in" to record the incoming caller's audio, "out" to record the outgoing caller's audio, and "both" to record both.]
     *      @type $reqData['TimeLimit'][The maximum duration of the recording. Allowed value is an integer greater than 0.]
     *      @type $reqData['CallbackUrl'][A URL that will be requested when the recording ends, sending information about the recording. The longer the recording, the longer the delay in processing the recording and requesting the CallbackUrl. Url length is limited to 200 characters.]
     *      @type $reqData['FileFormat'][mp3|Specifies the file format of the recording. Allowed values are "mp3" or "wav" - any other value will default to "mp3".]
     *      @type $reqData['TrimSilence'][false|Trims all silence from the beginning of the recording. Allowed values are "true" or "false" - any other value will default to "false".]
     *      @type $reqData['Transcribe'][false|Specifies if this recording should be transcribed. Allowed values are "true" and "false" - all other values will default to "false".]
     *      @type $reqData['TranscribeQuality'][auto|Specifies the quality of the transcription. Allowed values are "auto" for automated transcriptions and "hybrid" for human-reviewed transcriptions - all other values will default to "auto".]
     *      @type $reqData['TranscribeCallback'][A URL that will be requested when the call ends, sending information about the transcription. The longer the recording, the longer the delay in processing the transcription and requesting the TranscribeCallback. Url length is limited to 200 characters.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function recordLiveCall( Array $reqData = array() ){
        if( !isset($reqData['CallSid']) || is_null($reqData['CallSid']) ) throw new ZangException("'CallSid' is not set.");
        elseif( !isset($reqData['Record']) || is_null($reqData['Record']) ) throw new ZangException("'Record' is not set.");
        else {
            $sid = $reqData['CallSid'];
            unset($reqData['CallSid']);
            return self::$_instance->create(array("calls", $sid, "Recordings"), $reqData);
        }
    }

    /**
     * options include restricting to one caller and looping
     * @param array $reqData {
     *      @type $reqData['CallSid'][Call SID.]
     *      @type $reqData['AudioUrl'][A URL returning the sound file to play. Progressive downloads and SHOUTCAST streaming are also supported.]
     *      @type $reqData['Direction'][both|Specifies which caller will hear the played audio. Allowed values are "in" to play audio to the incoming caller, "out" to play to the outgoing caller, and "both" to play the audio to both callers.]
     *      @type $reqData['Loop'][false|Specifies whether the audio will loop. Allowed values are "true" and "false".]
    ]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function playAudioToLiveCall( Array $reqData ){
        if( !isset($reqData['CallSid']) || is_null($reqData['CallSid']) ) throw new ZangException("'CallSid' is not set.");
        elseif( !isset($reqData['AudioUrl']) || is_null($reqData['AudioUrl']) ) throw new ZangException("'AudioUrl' is not set.");
        else {
            $sid = $reqData['CallSid'];
            unset($reqData['CallSid']);
            return self::$_instance->create(array("calls", $sid, "Play"), $reqData);
        }
    }

    /**
     * applies voice effect on the call
     * @param array $reqData {
     *      @type $reqData['CallSid'][Call SID.]
     *      @type $reqData['AudioDirection'][out|Specifies which caller should have their voice modified. Allowed values are "in" for the incoming caller and "out" for the outgoing caller. This value can be changed as often as you like to control live call flow.]
     *      @type $reqData['Pitch'][	1|Sets the pitch. The lower the value, the lower the tone. Allowed values are integers greater than 0.]
     *      @type $reqData['PitchSemiTones'][1|Changes the pitch of audio in semitone intervals. Allowed values are integers between -14 and 14.]
     *      @type $reqData['PitchOctaves'][1|Changes the pitch of the audio in octave intervals. Allowed values are integers between -1 and 1.]
     *      @type $reqData['Rate'][1|Sets the rate. The lower the value, the lower the rate. Allowed values are integers greater than 0.]
     *      @type $reqData['Tempo'][1|Sets the tempo. The lower the value, the slower the tempo. Allowed values are integers greater than 0.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function applyVoiceEffect( Array $reqData = array() ){
        if( !isset($reqData['CallSid']) || is_null($reqData['CallSid']) ) throw new ZangException("'CallSid' is not set.");
        else {
            $sid = $reqData['CallSid'];
            unset($reqData['CallSid']);
            return self::$_instance->create(array("calls", $sid, "Effect"), $reqData);
        }
    }
}