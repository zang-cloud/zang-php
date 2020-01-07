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


class IncomingPhoneNumbers extends Zang_Related {

    /**
     * Singleton instance container
     * @var IncomingPhoneNumbers|null
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
     * @return IncomingPhoneNumbers
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
     * shows info on an incoming phone number
     * @param $reqData array {
     *      @type string $reqData['IncomingNumberSid'][Incoming number SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewIncomingNumber( Array $reqData=array() ){
        if( !isset($reqData['IncomingNumberSid']) || is_null( $reqData['IncomingNumberSid'] )) throw new ZangException("'IncomingNumberSid' is not set.");
        else{
            return self::$_instance->get(array( 'incoming_phone_numbers', $reqData['IncomingNumberSid'] ));
        }
    }

    /**
     * shows info on all incoming numbers associated with some account
     * @param $reqData array {
     *      @type string $reqData['Contains'][List numbers containing certain digits.]
     *      @type string $reqData['FriendlyName'][Specifies that only IncomingPhoneNumber resources matching the input FriendlyName should be returned in the list request.]
     *      @type int $reqData['Page'][1|Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'][50|Used to specify the amount of list items to return per page.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listIncomingNumbers( Array $reqData=array() ){
            return self::$_instance->get('incoming_phone_numbers', $reqData);
    }

    /**
     * purchases a new incoming number
     * @param $reqData array {
     *      @type string $reqData['PhoneNumber'][A specific available phone number you wish to add.]
     *      @type string $reqData['AreaCode'][The area code from which a random available number will be added.]
     *      @type string $reqData['FriendlyName'][User generated name for the incoming number.]
     *      @type string $reqData['VoiceUrl'][The URL returning InboundXML incoming calls should execute when connected.]
     *      @type string $reqData['VoiceMethod'][POST|Specifies the HTTP method used to request the VoiceUrl once incoming call connects.]
     *      @type string $reqData['VoiceFallbackUrl'][URL used if any errors occur during execution of InboundXML on a call or at initial request of the VoiceUrl.]
     *      @type string $reqData['VoiceFallbackMethod'][POST|Specifies the HTTP method used to request the VoiceFallbackUrl once incoming call connects.]
     *      @type string $reqData['VoiceCallerIdLookup'][Look up the caller’s caller-ID name from the CNAM database (additional charges apply).]
     *      @type string $reqData['SmsUrl'][The URL returning InboundXML incoming phone numbers should execute when receiving an SMS.]
     *      @type string $reqData['SmsMethod'][POST|Specifies the HTTP method used to request the SmsUrl once an incoming SMS is received.]
     *      @type string $reqData['SmsFallbackUrl'][URL used if any errors occur during execution of InboundXML from an SMS or at initial request of the SmsUrl.]
     *      @type string $reqData['SmsFallbackMethod'][POST|Specifies the HTTP method used to request the SmsFallbackUrl.]
     *      @type string $reqData['HeartbeatUrl'][URL that can be used to monitor the phone number.]
     *      @type string $reqData['HeartbeatMethod'][POST|The HTTP method Avaya CPaaS will use when requesting the HeartbeatURL.]
     *      @type string $reqData['StatusCallback'][URL that can be requested to receive notification when and how incoming call has ended.]
     *      @type string $reqData['StatusCallbackMethod'][POST|The HTTP method Avaya CPaaS will use when requesting the HangupCallback URL.]
     *      @type string $reqData['HangupCallback'][This is a StatusCallback clone that will be phased out in future versions.]
     *      @type string $reqData['HangupCallbackMethod'][POST|This is a StatusCallbackMethod clone that will be phased out in future versions.]
     *      @type string $reqData['VoiceApplicationSid'][The SID of the Voice Application you wish to associate with this incoming number.]
     *      @type string $reqData['SmsApplicationSid'][The SID of the SMS Application you wish to associate with this incoming number.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function purchaseIncomingNumbers( Array $reqData=array() ){
        return self::$_instance->create('incoming_phone_numbers', $reqData);
    }

    /**
     * updates an incoming phone number data
     * @param $reqData array {
     *      @type string $reqData['IncomingPhoneNumberSid'][34 characters long unique incoming phone number identifier.]
     *      @type string $reqData['FriendlyName'][User generated name for the incoming number.]
     *      @type string $reqData['VoiceUrl'][The URL returning InboundXML incoming calls should execute when connected.]
     *      @type string $reqData['VoiceMethod'][POST|Specifies the HTTP method used to request the VoiceUrl once incoming call connects.]
     *      @type string $reqData['VoiceFallbackUrl'][URL used if any errors occur during execution of InboundXML on a call or at initial request of the VoiceUrl.]
     *      @type string $reqData['VoiceFallbackMethod'][POST|Specifies the HTTP method used to request the VoiceFallbackUrl once incoming call connects.]
     *      @type string $reqData['VoiceCallerIdLookup'][Look up the caller’s caller-ID name from the CNAM database (additional charges apply).]
     *      @type string $reqData['SmsUrl'][The URL returning InboundXML incoming phone numbers should execute when receiving an SMS.]
     *      @type string $reqData['SmsMethod'][POST|Specifies the HTTP method used to request the SmsUrl once an incoming SMS is received.]
     *      @type string $reqData['SmsFallbackUrl'][URL used if any errors occur during execution of InboundXML from an SMS or at initial request of the SmsUrl.]
     *      @type string $reqData['SmsFallbackMethod'][POST|Specifies the HTTP method used to request the SmsFallbackUrl.]
     *      @type string $reqData['HeartbeatUrl'][URL that can be used to monitor the phone number.]
     *      @type string $reqData['HeartbeatMethod'][POST|The HTTP method Avaya CPaaS will use when requesting the HeartbeatURL.]
     *      @type string $reqData['StatusCallback'][URL that can be requested to receive notification when and how incoming call has ended.]
     *      @type string $reqData['StatusCallbackMethod'][POST|The HTTP method Avaya CPaaS will use when requesting the HangupCallback URL.]
     *      @type string $reqData['HangupCallback'][This is a StatusCallback clone that will be phased out in future versions.]
     *      @type string $reqData['HangupCallbackMethod'][POST|This is a StatusCallbackMethod clone that will be phased out in future versions.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function updateIncomingNumbers( Array $reqData=array() ){
        if( !isset($reqData['IncomingPhoneNumberSid']) || is_null( $reqData['IncomingPhoneNumberSid'] )) throw new ZangException("'IncomingPhoneNumberSid' is not set.");
        else {
            $sid = $reqData['IncomingPhoneNumberSid'];
            unset($reqData['IncomingPhoneNumberSid']);
            return self::$_instance->update(array('incoming_phone_numbers',$sid), $reqData);
        }
    }

    /**
     * deletes an incoming phone number
     * @param $reqData array {
     *      @type string $reqData['IncomingPhoneNumberSid'][34 characters long unique incoming phone number identifier.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteIncomingNumbers( Array $reqData=array() ){
        if( !isset($reqData['IncomingPhoneNumberSid']) || is_null( $reqData['IncomingPhoneNumberSid'] )) throw new ZangException("'IncomingPhoneNumberSid' is not set.");
        else {
            $sid = $reqData['IncomingPhoneNumberSid'];
            unset($reqData['IncomingPhoneNumberSid']);
            return self::$_instance->delete(array('incoming_phone_numbers',$sid));
        }
    }
}