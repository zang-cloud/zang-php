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



class Applications extends Zang_Related {

    /**
     * Singleton instance container
     * @var Applications|null
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
     * @return Applications
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
     * shows info on a application
     * @param $reqData array {
     *      @type string $reqData['ApplicationSid'][Application SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewApplication( Array $reqData=array() ){
        if( !isset($reqData['ApplicationSid']) || is_null( $reqData['ApplicationSid'] )) throw new ZangException("'ApplicationSid' is not set.");
        else{
            return self::$_instance->get(array( 'applications', $reqData['ApplicationSid']  ));
        }

    }

    /**
     * creates a new application
     * @param $reqData array {
     *      @type string $reqData['FriendlyName'][The name used to identify this application. If this is not included at the initial POST, it is given the value of the application sid.]
     *      @type string $reqData['VoiceUrl'][http://telapi.com/ivr/welcome/call|The URL requested once the call connects. This URL must be valid and should return InboundXML containing instructions on how to process your call. A badly formatted Url will NOT fallback to the FallbackUrl but return an error without placing the call. Url length is limited to 200 characters.]
     *      @type string $reqData['VoiceMethod'][POST|The HTTP method used to request the URL once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['VoiceFallbackUrl'][URL used if the required URL is unavailable or if any errors occur during execution of the InboundXML returned by the required URL. Url length is limited to 200 characters.]
     *      @type string $reqData['VoiceFallbackMethod'][POST|The HTTP method used to request the FallbackUrl once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['VoiceCallerIdLookup'][false|Look up the caller’s caller-ID name from the CNAM database (additional charges apply). Allowed values are "true" and "false".]
     *      @type string $reqData['SmsUrl'][http://telapi.com/ivr/welcome/sms|The URL requested when an SMS is received. This URL must be valid and should return InboundXML containing instructions on how to process the SMS. A badly formatted URL will NOT fallback to the FallbackUrl but return an error without placing the call. URL length is limited to 200 characters.]
     *      @type string $reqData['SmsMethod'][	POST|The HTTP method used to request the URL when an SMS is received. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['SmsFallbackUrl'][URL used if the required URL is unavailable or if any errors occur during execution of the InboundXML returned by the required URL. Url length is limited to 200 characters.]
     *      @type string $reqData['SmsFallbackMethod'][POST|The HTTP method used to request the FallbackUrl once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['HeartbeatUrl'][A URL that will be requested every 60 seconds during the call, sending information about the call. The HeartbeatUrl will NOT be requested unless at least 60 seconds of call time have elapsed. URL length is limited to 200 characters.]
     *      @type string $reqData['HeartbeatMethod'][POST|The HTTP method used to request the HeartbeatUrl. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['StatusCallback'][A URL that will be requested when the call connects and ends, sending information about the call. URL length is limited to 200 characters.]
     *      @type string $reqData['StatusCallbackMethod'][POST|The HTTP method used to request the StatusCallback URL. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['HangupCallback'][This is a StatusCallback clone that will be phased out in future versions.]
     *      @type string $reqData['HangupCallbackMethod'][This is a StatusCallbackMethod clone that will be phased out in future versions.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function createApplication( Array $reqData=array() ){
        return self::$_instance->create("applications", $reqData);
    }

    /**
     * updates application data
     * @param $reqData array {
     *      @type string $reqData['ApplicationSid'][Application SID.]
     *      @type string $reqData['FriendlyName'][The name used to identify this application. If this is not included at the initial POST, it is given the value of the application sid.]
     *      @type string $reqData['VoiceUrl'][http://telapi.com/ivr/welcome/call|The URL requested once the call connects. This URL must be valid and should return InboundXML containing instructions on how to process your call. A badly formatted Url will NOT fallback to the FallbackUrl but return an error without placing the call. Url length is limited to 200 characters.]
     *      @type string $reqData['VoiceMethod'][POST|The HTTP method used to request the URL once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['VoiceFallbackUrl'][URL used if the required URL is unavailable or if any errors occur during execution of the InboundXML returned by the required URL. Url length is limited to 200 characters.]
     *      @type string $reqData['VoiceFallbackMethod'][POST|The HTTP method used to request the FallbackUrl once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['VoiceCallerIdLookup'][false|Look up the caller’s caller-ID name from the CNAM database (additional charges apply). Allowed values are "true" and "false".]
     *      @type string $reqData['SmsUrl'][http://telapi.com/ivr/welcome/sms|The URL requested when an SMS is received. This URL must be valid and should return InboundXML containing instructions on how to process the SMS. A badly formatted URL will NOT fallback to the FallbackUrl but return an error without placing the call. URL length is limited to 200 characters.]
     *      @type string $reqData['SmsMethod'][	POST|The HTTP method used to request the URL when an SMS is received. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['SmsFallbackUrl'][URL used if the required URL is unavailable or if any errors occur during execution of the InboundXML returned by the required URL. Url length is limited to 200 characters.]
     *      @type string $reqData['SmsFallbackMethod'][POST|The HTTP method used to request the FallbackUrl once the call connects. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['HeartbeatUrl'][A URL that will be requested every 60 seconds during the call, sending information about the call. The HeartbeatUrl will NOT be requested unless at least 60 seconds of call time have elapsed. URL length is limited to 200 characters.]
     *      @type string $reqData['HeartbeatMethod'][POST|The HTTP method used to request the HeartbeatUrl. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['StatusCallback'][A URL that will be requested when the call connects and ends, sending information about the call. URL length is limited to 200 characters.]
     *      @type string $reqData['StatusCallbackMethod'][POST|The HTTP method used to request the StatusCallback URL. Valid parameters are GET and POST - any other value will default to POST.]
     *      @type string $reqData['HangupCallback'][This is a StatusCallback clone that will be phased out in future versions.]
     *      @type string $reqData['HangupCallbackMethod'][This is a StatusCallbackMethod clone that will be phased out in future versions.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function updateApplication( Array $reqData=array() ){
        if( !isset($reqData['ApplicationSid']) || is_null( $reqData['ApplicationSid'] )) throw new ZangException("'ApplicationSid' is not set.");
        else {
            $applicationSid = $reqData['ApplicationSid'];
            unset($reqData['ApplicationSid']);
            return self::$_instance->update(array("applications", $applicationSid), $reqData);
        }
    }

    /**
     * deletes application
     * @param $reqData array {
     *      @type string $reqData['ApplicationSid'][Application SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteApplication( Array $reqData=array() ){
        if( !isset($reqData['ApplicationSid']) || is_null( $reqData['ApplicationSid'] )) throw new ZangException("'ApplicationSid' is not set.");
        else {
            $applicationSid = $reqData['ApplicationSid'];
            unset($reqData['ApplicationSid']);
            return self::$_instance->delete(array("applications", $applicationSid));
        }
    }

    /**
     * shows info on all applications associated with some account
     * @param $reqData array {
     *      @type string $reqData['FriendlyName'][Filters by the application's FriendlyName.]
     *      @type string $reqData['Page'][Used to return a particular page within the list.]
     *      @type string $reqData['PageSize'][Used to specify the amount of list items to return per page.]

     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listApplication( Array $reqData=array() ){
        return self::$_instance->get("applications", $reqData);
    }
}