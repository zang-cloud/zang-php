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



class SipCredentials extends Zang_Related {

    /**
     * Singleton instance container
     * @var SipCredentials|null
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
     * @return SipCredentials
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
     * view info on SIP domain credentials list
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewCredentialsList( Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_credentials', $reqData['CLSid'], ));
        }
    }

    /**
     * show info on SIP domain credentials lists
     * @return Zang_Connector
     * @throws ZangException
     */
    function listCredentialsList(){
            return self::$_instance->get('sip_credentials');
    }

    /**
     * creates SIP domain credentials list
     * @param $reqData array {
     *      @type string $reqData['FriendlyName'][A human readable name for this credential list.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function createCredentialsList( Array $reqData=array() ){
        if( !isset($reqData['FriendlyName']) || is_null( $reqData['FriendlyName'] )) throw new ZangException("'FriendlyName' is not set.");
        else {
            return self::$_instance->create("sip_credentials", $reqData);
        }
    }

    /**
     * updates info for credentials list
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID]
     *      @type string $reqData['FriendlyName'][A human readable name for this credential list.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function updateCredentialsList( Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        else {
            $cliSid = $reqData['CLSid'];
            unset($reqData['CLSid']);
            return self::$_instance->update(array("sip_credentials", $cliSid), $reqData);
        }
    }

    /**
     * view SIP domain credentials information
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID.]
     *      @type string $reqData['CredentialSid'][Credential SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewCredential( Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        elseif( !isset($reqData['CredentialSid']) || is_null( $reqData['CredentialSid'] )) throw new ZangException("'CredentialSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_credentials', $reqData['CLSid'], "Credentials", $reqData['CredentialSid'] ));
        }
    }

    /**
     * deletes SIP domain credentials list
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteCredentialsList( Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        else{
            return self::$_instance->delete(array( 'sip_credentials', $reqData['CLSid']  ));
        }
    }

    /**
     * show info on all credentials attached to a particular credentials list
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listCredentials(Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        else {
            return self::$_instance->get(array('sip_credentials', $reqData['CLSid'], "Credentials" ));
        }
    }

    /**
     * create SIP domain credentials
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID]
     *      @type string $reqData['Username'][The username used to identify this credential.]
     *      @type string $reqData['Password'][The password used to authenticate this credential]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function createCredential( Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        elseif( !isset($reqData['Username']) || is_null( $reqData['Username'] )) throw new ZangException("'Username' is not set.");
        elseif( !isset($reqData['Password']) || is_null( $reqData['Password'] )) throw new ZangException("'Password' is not set.");
        else {
            $clSid = $reqData['CLSid'];
            unset($reqData['CLSid']);
            return self::$_instance->create(array("sip_credentials", $clSid, "Credentials"), $reqData);
        }
    }

    /**
     * update SIP domain credentials information
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID.]
     *      @type string $reqData['CredentialSid'][Credential SID.]
     *      @type string $reqData['Password'][The password used to authenticate this credential.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function updateCredential( Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        elseif( !isset($reqData['CredentialSid']) || is_null( $reqData['CredentialSid'] )) throw new ZangException("'CredentialSid' is not set.");
        elseif( !isset($reqData['Password']) || is_null( $reqData['Password'] )) throw new ZangException("'Password' is not set.");
        else{
            $clSid = $reqData['CLSid'];
            unset($reqData['CLSid']);
            $creSid = $reqData['CredentialSid'];
            unset($reqData['CredentialSid']);
            return self::$_instance->get(array( 'sip_credentials', $clSid, "Credentials", $creSid ), $reqData);
        }
    }


    /**
     * deletes SIP domain credentials
     * @param $reqData array {
     *      @type string $reqData['CLSid'][Credentials list SID.]
     *      @type string $reqData['CredentialSid'][Credential SID.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteCredential( Array $reqData=array() ){
        if( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        elseif( !isset($reqData['CredentialSid']) || is_null( $reqData['CredentialSid'] )) throw new ZangException("'CredentialSid' is not set.");
        else{
            return self::$_instance->delete(array( 'sip_credentials', $reqData['CLSid'], "Credentials", $reqData['CredentialSid'] ));
        }
    }
}