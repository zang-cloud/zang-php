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



class ApplicationClients extends Zang_Related {

    /**
     * Singleton instance container
     * @var ApplicationClients|null
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
     * @return ApplicationClients
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
     * creates a new application
     * @param $reqData array {
     *      @type string $reqData['ApplicationSid'][Application SID]
     *      @type string $reqData['Nickname'][The name used to identify this application client.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function createApplicationClient( Array $reqData=array() ){
        if( !isset($reqData['ApplicationSid']) || is_null( $reqData['ApplicationSid'] )) throw new ZangException("'ApplicationSid' is not set.");
        elseif( !isset($reqData['Nickname']) || is_null( $reqData['Nickname'] )) throw new ZangException("'Nickname' is not set.");
        else {
            $applicationSid = $reqData['ApplicationSid'];
            unset($reqData['ApplicationSid']);
            return self::$_instance->create(array("applications", $applicationSid, "Clients", "Tokens"), $reqData);
        }
    }

    /**
     * View all information about an application client
     * @param $reqData array {
     *      @type string $reqData['ApplicationSid'][Application SID.]
     *      @type string $reqData['ClientSid'][Application Client SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewApplicationClient( Array $reqData=array() ){
        if( !isset($reqData['ApplicationSid']) || is_null( $reqData['ApplicationSid'] )) throw new ZangException("'ApplicationSid' is not set.");
        elseif( !isset($reqData['ClientSid']) || is_null( $reqData['ClientSid'] )) throw new ZangException("'ClientSid' is not set.");
        else{
            return self::$_instance->get(array( 'applications', $reqData['ApplicationSid'], "Clients", $reqData['ClientSid']  ));
        }
    }

    /**
     * lists available application clients
     * @param $reqData array {
     *      @type string $reqData['ApplicationSid'][Application SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listApplicationClient( Array $reqData=array() ){
        if( !isset($reqData['ApplicationSid']) || is_null( $reqData['ApplicationSid'] )) throw new ZangException("'ApplicationSid' is not set.");
        else{
            return self::$_instance->get(array( 'applications', $reqData['ApplicationSid'], "Clients" ));
        }
    }
}