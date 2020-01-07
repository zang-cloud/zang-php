<?php
/**
 * Created by PhpStorm.
 * User: Jaymin
 * Date: 17.10.2017.
 * Time: 11:52
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


class Mms extends Zang_Related {

    /**
     * Singleton instance container
     * @var Mms|null
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
     * @return Mms
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
     * sends MMS message
     * @param array $reqData {
     *      @type string  $reqData['To'] [Must be an SMS capable number. The value does not have to be in any specific format.]
     *      @type string  $reqData['From'] [Must be a Avaya CPaaS number associated with your account. The value does not have to be in any specific format.]
     *      @type string  $reqData['Body'] [Text of the SMS to be sent.]
     *      @type string  $reqData['StatusCallback'] [The URL that will be sent information about the SMS. Url length is limited to 200 characters.]
     *      @type string  $reqData['StatusCallbackMethod'] [POST|The HTTP method used to request the StatusCallback. Valid parameters are GET and POST.]
     *      @type string  $reqData['MediaUrl'] [URL of image that will send as a message. It is required.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function sendMms ( Array $reqData=array() ){
        if( !isset($reqData['To']) || is_null( $reqData['To'] )) throw new ZangException("'To' is not set.");
        elseif( !isset($reqData['Body']) || is_null( $reqData['Body'] )) throw new ZangException("'Body' is not set.");
        elseif( !isset($reqData['MediaUrl']) || is_null( $reqData['MediaUrl'] )) throw new ZangException("'Media URL' is not set.");
        elseif( isset($smsData['StatusCallback']) && (filter_var($smsData['StatusCallback'], FILTER_VALIDATE_URL) === FALSE ||  strlen($smsData['StatusCallback']) > 200) ) throw new ZangException("Wrong 'StatusCallback' value.");
        else {
            return self::$_instance->create('mms_messages', $reqData);
        }
    }
}