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


class Usage extends Zang_Related {

    /**
     * Singleton instance container
     * @var Usage|null
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
     * @return Usage
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
     * view the usage of an item returned by List Usages
     * @param array $reqData {
     *      @type $reqData['UsageSid'][Usage SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewUsage( Array $reqData = array() ){
        if( !isset($reqData['UsageSid']) || is_null($reqData['UsageSid']) ) throw new ZangException("'UsageSid' is not set.");
        else{
            return self::$_instance->get(array( 'usages', $reqData['UsageSid'] ));
        }

    }

    /**
     * complete list of all usages of your account
     * @params array $reqData
     *      @type string $reqData['Day'] [Filters usage by day of month. If no month is specified then defaults to current month. Allowed values are integers between 1 and 31 depending on the month. Leading 0s will be ignored.]
     *      @type string $reqData['Month'] [Filters usage by month. Allowed values are integers between 1 and 12. Leading 0s will be ignored.]
     *      @type string $reqData['Year'] [Filters usage by year. Allowed values are valid years in integer form such as "2014".]
     *      @type string $reqData['Product'] [Filters usage by a specific “product” of Zang. Each product is uniquely identified by an integer. For example: Product=1, would return all outbound call usage. The integer assigned to each product is listed below.]
     *      @type int $reqData['Page'] [Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'] [Used to specify the amount of list items to return per page.]
     * @return Zang_Connector
     * @throws ZangException
     * @return Zang_Connector
     */
    function listUsage(Array $reqData = array() ){
        return self::$_instance->get('usages', $reqData );
    }
}