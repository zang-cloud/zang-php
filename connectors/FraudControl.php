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



class FraudControl extends Zang_Related {

    /**
     * Singleton instance container
     * @var FraudControl|null
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
     * @return FraudControl
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
     * restricts outbound calls and sms messages to some destination
     * @param $reqData array {
     *      @type string $reqData['CountryCode'][Country code.]
     *      @type string $reqData['MobileEnabled'][true|Mobile status for the destination. If false, all mobile call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['LandlineEnabled'][true|Landline status for the destination. If false, all landline call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['SmsEnabled'][true|SMS status for the destination. If false, all SMS activity will be rejected or disabled. Allowed values are "true" and "false".]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function blockDestination( Array $reqData=array() ){
        if( !isset($reqData['CountryCode']) || is_null( $reqData['CountryCode'] )) throw new ZangException("'CountryCode' is not set.");
        else {
            $CountryCode = $reqData['CountryCode'];
            unset($reqData['CountryCode']);
            return self::$_instance->create(array('fraud_block', $CountryCode), $reqData);
        }
    }

    /**
     * authorizes previously blocked destination for outbound calls and sms messages
     * @param $reqData array {
     *      @type string $reqData['CountryCode'][Country code.]
     *      @type string $reqData['MobileEnabled'][true|Mobile status for the destination. If false, all mobile call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['LandlineEnabled'][true|Landline status for the destination. If false, all landline call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['SmsEnabled'][true|SMS status for the destination. If false, all SMS activity will be rejected or disabled. Allowed values are "true" and "false".]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function authorizeDestination( Array $reqData=array() ){
        if( !isset($reqData['CountryCode']) || is_null( $reqData['CountryCode'] )) throw new ZangException("'CountryCode' is not set.");
        else {
            $CountryCode = $reqData['CountryCode'];
            unset($reqData['CountryCode']);
            return self::$_instance->create(array('fraud_authorize', $CountryCode), $reqData);
        }
    }

    /**
     * extends a destinations authorization expiration by 30 days
     * @param $reqData array {
     *      @type string $reqData['CountryCode'][Country code.]
     *      @type string $reqData['MobileEnabled'][true|Mobile status for the destination. If false, all mobile call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['LandlineEnabled'][true|Landline status for the destination. If false, all landline call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['SmsEnabled'][true|SMS status for the destination. If false, all SMS activity will be rejected or disabled. Allowed values are "true" and "false".]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function extendDestinationAuthorization( Array $reqData=array() ){
        if( !isset($reqData['CountryCode']) || is_null( $reqData['CountryCode'] )) throw new ZangException("'CountryCode' is not set.");
        else {
            $CountryCode = $reqData['CountryCode'];
            unset($reqData['CountryCode']);
            return self::$_instance->create(array('fraud_extend', $CountryCode), $reqData);
        }
    }
    /**
     * permanently authorizes destination that may have been blocked by our automated fraud detection system
     * @param $reqData array {
     *      @type string $reqData['CountryCode'][Country code.]
     *      @type string $reqData['MobileEnabled'][true|Mobile status for the destination. If false, all mobile call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['LandlineEnabled'][true|Landline status for the destination. If false, all landline call activity will be rejected or disabled. Allowed values are "true" and "false".]
     *      @type string $reqData['SmsEnabled'][true|SMS status for the destination. If false, all SMS activity will be rejected or disabled. Allowed values are "true" and "false".]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function whitelistDestination( Array $reqData=array() ){
        if( !isset($reqData['CountryCode']) || is_null( $reqData['CountryCode'] )) throw new ZangException("'CountryCode' is not set.");
        else {
            $CountryCode = $reqData['CountryCode'];
            unset($reqData['CountryCode']);
            return self::$_instance->create(array('fraud_whitelist', $CountryCode), $reqData);
        }
    }

    /**
     * shows information on all fraud control resources associated with some account
     * @param $reqData array {
     *      @type int $reqData['Page'][Used to return a particular page within the list..]
     *      @type int $reqData['PageSize'][Used to specify the amount of list items to return per page..]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listFraudControlResources( Array $reqData=array() ){
        return self::$_instance->get('fraud', $reqData);
    }

}