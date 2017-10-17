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


class SipIpAccessControlList extends Zang_Related {

    /**
     * Singleton instance container
     * @var SipIpAccessControlList|null
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
     * @return SipIpAccessControlList
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
     * view information for IP access control list
     * @param $reqData array {
     *      @type string $reqData['IpAccessControlListSid'][IP access control list SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewIPACL( Array $reqData=array() ){
        if( !isset($reqData['IpAccessControlListSid']) || is_null( $reqData['IpAccessControlListSid'] )) throw new ZangException("'IpAccessControlListSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_ipacl', $reqData['IpAccessControlListSid'] ));
        }
    }

    /**
     * list all IP access control lists associated with a particular account
     * @param $reqData array {
     *      @type int $reqData['Page'][Used to return a particular page within the list.]
     *      @type int $reqData['PageSize'][Used to specify the amount of list items to return per page.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listIPACLs( Array $reqData=array() ){
        return self::$_instance->get('sip_ipacl', $reqData);
    }

    /**
     * create IP access control list
     * @param $reqData array {
     *      @type string $reqData['FriendlyName'][A human-readable name associated with this IP ACL.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function createIPACL( Array $reqData=array() ){
        if( !isset($reqData['FriendlyName']) || is_null( $reqData['FriendlyName'] )) throw new ZangException("'FriendlyName' is not set.");
        else {
            return self::$_instance->create("sip_ipacl", $reqData);
        }
    }

    /**
     * updates information for IP access control list
     * @param $reqData array {
     *      @type string $reqData['IpAccessControlListSid'][IP access control list SID]
     *      @type string $reqData['FriendlyName'][A human readable name for this credential list.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function updateIPACL( Array $reqData=array() ){
        if( !isset($reqData['IpAccessControlListSid']) || is_null( $reqData['IpAccessControlListSid'] )) throw new ZangException("'IpAccessControlListSid' is not set.");
        elseif( !isset($reqData['FriendlyName']) || is_null( $reqData['FriendlyName'] )) throw new ZangException("'FriendlyName' is not set.");
        else {
            $sid= $reqData['IpAccessControlListSid'];
            unset($reqData['IpAccessControlListSid']);
            return self::$_instance->update(array("sip_ipacl", $sid), $reqData);
        }
    }

    /**
     * deletes IP access control list
     * @param $reqData array {
     *      @type string $reqData['IpAccessControlListSid'][IP access control list SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteIPACL( Array $reqData=array() ){
        if( !isset($reqData['IpAccessControlListSid']) || is_null( $reqData['IpAccessControlListSid'] )) throw new ZangException("'IpAccessControlListSid' is not set.");
        else {
            return self::$_instance->delete(array("sip_ipacl", $reqData['IpAccessControlListSid']));
        }
    }

    /**
     * view information on IP access control list IP address
     * @param $reqData array {
     *      @type string $reqData['AclSid'][IP access control list SID.]
     *      @type string $reqData['IpSid'][Access control list IP address SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewACLIP( Array $reqData=array() ){
        if( !isset($reqData['AclSid']) || is_null( $reqData['AclSid'] )) throw new ZangException("'AclSid' is not set.");
        elseif( !isset($reqData['IpSid']) || is_null( $reqData['IpSid'] )) throw new ZangException("'IpSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_ipacl', $reqData['AclSid'], "IpAddresses", $reqData['IpSid'] ));
        }
    }

    /**
     * lists IP addresses attached to some IP access control list
     * @param $reqData array {
     *      @type string $reqData['AclSid'][IP access control list SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listACLIP( Array $reqData=array() ){
        if( !isset($reqData['AclSid']) || is_null( $reqData['AclSid'] )) throw new ZangException("'AclSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_ipacl', $reqData['AclSid'], "IpAddresses" ));
        }
    }

    /**
     * add new IP for access control list
     * @param $reqData array {
     *      @type string $reqData['AclSid'][IP access control list SID.]
     *      @type string $reqData['FriendlyName'][A human-readable name associated with this IP ACL.]
     *      @type string $reqData['IpAddress'][An IP address from which you wish to accept traffic. At this time, only IPv4 supported.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function addACLIP( Array $reqData=array() ){
        if( !isset($reqData['AclSid']) || is_null( $reqData['AclSid'] )) throw new ZangException("'AclSid' is not set.");
        elseif( !isset($reqData['FriendlyName']) || is_null( $reqData['FriendlyName'] )) throw new ZangException("'FriendlyName' is not set.");
        elseif( !isset($reqData['IpAddress']) || is_null( $reqData['IpAddress'] )) throw new ZangException("'IpAddress' is not set.");
        else{
            $sid = $reqData['AclSid'];
            unset($reqData['AclSid']);
            return self::$_instance->create(array( 'sip_ipacl', $sid, "IpAddresses" ), $reqData);
        }
    }

    /**
     * updates IP address for IP access control list
     * @param $reqData array {
     *      @type string $reqData['AclSid'][IP access control list SID.]
     *      @type string $reqData['IpSid'][Access control list IP address SID.]
     *      @type string $reqData['FriendlyName'][A human-readable name associated with this IP ACL.]
     *      @type string $reqData['IpAddress'][An IP address from which you wish to accept traffic. At this time, only IPv4 supported.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function updateACLIP( Array $reqData=array() ){
        if( !isset($reqData['AclSid']) || is_null( $reqData['AclSid'] )) throw new ZangException("'AclSid' is not set.");
        elseif( !isset($reqData['IpSid']) || is_null( $reqData['IpSid'] )) throw new ZangException("'IpSid' is not set.");
        else{
            $aclSid = $reqData['AclSid'];
            unset($reqData['AclSid']);
            $ipSid = $reqData['IpSid'];
            unset($reqData['IpSid']);
            return self::$_instance->update(array( 'sip_ipacl', $aclSid, "IpAddresses", $ipSid ), $reqData);
        }
    }

    /**
     * deletes IP address from IP access control list
     * @param $reqData array {
     *      @type string $reqData['AclSid'][IP access control list SID.]
     *      @type string $reqData['IpSid'][Access control list IP address SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteACLIP( Array $reqData=array() ){
        if( !isset($reqData['AclSid']) || is_null( $reqData['AclSid'] )) throw new ZangException("'AclSid' is not set.");
        elseif( !isset($reqData['IpSid']) || is_null( $reqData['IpSid'] )) throw new ZangException("'IpSid' is not set.");
        else{
            $aclSid = $reqData['AclSid'];
            unset($reqData['AclSid']);
            $ipSid = $reqData['IpSid'];
            unset($reqData['IpSid']);
            return self::$_instance->delete(array( 'sip_ipacl', $aclSid, "IpAddresses", $ipSid ));
        }
    }
}