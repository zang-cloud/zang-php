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



class CarrierService extends Zang_Related {

    /**
     * Singleton instance container
     * @var CarrierService|null
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
     * @return CarrierService
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
     * The Carrier Lookup API allows you to retrieve additional information about a phone number.
     * @param $reqData array {
     *      @type string $reqData['PhoneNumber'][The number of the phone you are attempting to perform the CNAM lookup on. Multiple PhoneNumbers to lookup can be specified in a single request.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function carrierLookup( Array $reqData=array() ){
        if( !isset($reqData['PhoneNumber']) || is_null( $reqData['PhoneNumber'] )) throw new ZangException("'PhoneNumber' is not set.");
        else {
            return self::$_instance->create('carrier', $reqData);
        }
    }

    /**
     * shows info on all carrier lookups associated with some account
     * @param $reqData array {
     *      @type int $reqData['Page'][Used to return a particular page within the list..]
     *      @type int $reqData['PageSize'][Used to specify the amount of list items to return per page..]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function carrierLookupList( Array $reqData=array() ){
        return self::$_instance->get('carrier', $reqData);
    }


    /**
     * shows a CNAM information on some phone number
     * @param $reqData array {
     *      @type string $reqData['PhoneNumber'][The number of the phone you are attempting to perform the CNAM lookup on. Multiple PhoneNumbers to lookup can be specified in a single request.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function CNAMLookup( Array $reqData=array() ){
        if( !isset($reqData['PhoneNumber']) || is_null( $reqData['PhoneNumber'] )) throw new ZangException("'PhoneNumber' is not set.");
        else {
            return self::$_instance->create('cnam', $reqData);
        }
    }

    /**
     * shows info on all CNAM lookups associated with some account
     * @param $reqData array {
     *      @type int $reqData['Page'][Used to return a particular page within the list..]
     *      @type int $reqData['PageSize'][Used to specify the amount of list items to return per page..]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function CNAMLookupList( Array $reqData=array() ){
        return self::$_instance->get('cnam', $reqData);
    }

    /**
     * shows information on billing name address for some phone number
     * @param $reqData array {
     *      @type string $reqData['PhoneNumber'][The number of the phone you are attempting to perform the CNAM lookup on. Multiple PhoneNumbers to lookup can be specified in a single request.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function BnaLookup( Array $reqData=array() ){
        if( !isset($reqData['PhoneNumber']) || is_null( $reqData['PhoneNumber'] )) throw new ZangException("'PhoneNumber' is not set.");
        else {
            return self::$_instance->create('bna', $reqData);
        }
    }

    /**
     * shows info on all CNAM lookups associated with some account
     * @param $reqData array {
     *      @type int $reqData['Page'][Used to return a particular page within the list..]
     *      @type int $reqData['PageSize'][Used to specify the amount of list items to return per page..]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function BnaLookupList( Array $reqData=array() ){
        return self::$_instance->get('bna', $reqData);
    }
}