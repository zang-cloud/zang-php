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



class Notifications extends Zang_Related {

    /**
     * Singleton instance container
     * @var Notifications|null
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
     * @return Notifications
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
     * shows info on all notifications associated with some account
     * @param $reqData array {
     *      @type string $reqData['Log'][Specifies that only notifications with the given log level value should be listed. Allowed values are 1,2 or 3, where 2=INFO, 1=WARNING, 0=ERROR.]
     *      @type int $reqData['Page'][1|Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'][50|Used to specify the amount of list items to return per page.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listNotifications( Array $reqData=array() ){
        return self::$_instance->get('notifications', $reqData);
    }

    /**
     * shows info on all notifications associated with some account
     * @param $reqData array {
     *      @type string $reqData['NotificationSid'][Notification SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewNotification( Array $reqData=array() ){
        if( !isset($reqData['NotificationSid']) || is_null( $reqData['NotificationSid'] )) throw new ZangException("'NotificationSid' is not set.");
        else {
            return self::$_instance->get(array('notifications', $reqData['NotificationSid']));
        }
    }
}