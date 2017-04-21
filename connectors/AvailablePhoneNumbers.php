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



class AvailablePhoneNumbers extends Zang_Related {

    /**
     * Singleton instance container
     * @var AvailablePhoneNumbers|null
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
     * @return AvailablePhoneNumbers
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
     * shows information on all phone numbers available for purchasing
     * @param $reqData array {
     *      @type string $reqData['Country'][US|Two letter country code.]
     *      @type string $reqData['Type'][Local|Type of the phone number. Can be Local or Tollfree]
     *      @type int $reqData['Page'][1|Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'][50|Used to specify the amount of list items to return per page.]
     *      @type string $reqData['Contains'][Specifies the desired characters contained within the available numbers to list.]
     *      @type string $reqData['AreaCode'][Specifies the area code that the returned list of available numbers should be in. Only available for North American numbers]
     *      @type string $reqData['InRegion'][Specifies the desired region of the available numbers to be listed.]
     *      @type string $reqData['InPostalCode'][Specifies the desired postal code of the available numbers to be listed.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listAvailableNumbers( Array $reqData=array() ){
        if( !isset($reqData['Country']) || is_null( $reqData['Country'] )) throw new ZangException("'Country' is not set.");
        elseif( !isset($reqData['Type']) || is_null( $reqData['Type'] )) throw new ZangException("'Type' is not set.");
        else {
            $country = $reqData['Country'];
            unset($reqData['Country']);
            $type = $reqData['Type'];
            unset($reqData['Type']);
            return self::$_instance->get(array('available_phone_numbers', $country, $type), $reqData);
        }
    }
}