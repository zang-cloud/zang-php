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



class SipDomains extends Zang_Related {

    /**
     * Singleton instance container
     * @var SipDomains|null
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
     * @return SipDomains
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
     * get info on your SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function viewDomain( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_domains', $reqData['DomainSid'], ));
        }
    }

    /**
     * list all your SIP domains
     * @return Zang_Connector
     * @throws ZangException
     */
    function listDomains( Array $reqData=array() ){
        return self::$_instance->get('sip_domains', $reqData);
    }

    /**
     * create new SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainName'][An address on Avaya CPaaS uniquely associated with your account and through which all your SIP traffic is routed.]
     *      @type string $reqData['FriendlyName'][A human-readable name associated with this domain.]
     *      @type string $reqData['VoiceUrl'][The URL requested when a call is received by your domain.]
     *      @type string $reqData['VoiceMethod'][The HTTP method used when requesting the VoiceUrl.]
     *      @type string $reqData['VoiceFallbackUrl'][The URL requested if the VoiceUrl fails.]
     *      @type string $reqData['VoiceFallbackMethod'][The HTTP method used when requesting the VoiceFallbackUrl.]
     *      @type string $reqData['HeartbeatUrl'][URL that can be requested every 60 seconds during the call to notify of elapsed time and pass other general information.]
     *      @type string $reqData['HeartbeatMethod'][POST|Specifies the HTTP method used to request HeartbeatUrl.]
     *      @type string $reqData['VoiceStatusCallback'][The URL that Avaya CPaaS will use to send you status notifications regarding your SIP call.]
     *      @type string $reqData['VoiceStatusCallbackMethod'][The HTTP method used when requesting the VoiceStatusCallback.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function createDomain( Array $reqData=array() ){
        if( !isset($reqData['DomainName']) || is_null( $reqData['DomainName'] )) throw new ZangException("'DomainName' is not set.");
        else {
            return self::$_instance->create("sip_domains", $reqData);
        }
    }

    /**
     * updates SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     *      @type string $reqData['DomainName'][An address on Avaya CPaaS uniquely associated with your account and through which all your SIP traffic is routed.]
     *      @type string $reqData['FriendlyName'][A human-readable name associated with this domain.]
     *      @type string $reqData['VoiceUrl'][The URL requested when a call is received by your domain.]
     *      @type string $reqData['VoiceMethod'][The HTTP method used when requesting the VoiceUrl.]
     *      @type string $reqData['VoiceFallbackUrl'][The URL requested if the VoiceUrl fails.]
     *      @type string $reqData['VoiceFallbackMethod'][The HTTP method used when requesting the VoiceFallbackUrl.]
     *      @type string $reqData['HeartbeatUrl'][URL that can be requested every 60 seconds during the call to notify of elapsed time and pass other general information.]
     *      @type string $reqData['HeartbeatMethod'][POST|Specifies the HTTP method used to request HeartbeatUrl.]
     *      @type string $reqData['VoiceStatusCallback'][The URL that Avaya CPaaS will use to send you status notifications regarding your SIP call.]
     *      @type string $reqData['VoiceStatusCallbackMethod'][The HTTP method used when requesting the VoiceStatusCallback.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function updateDomain( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        else {
            $domainSid = $reqData['DomainSid'];
            unset($reqData['DomainSid']);
            return self::$_instance->update(array("sip_domains", $domainSid), $reqData);
        }
    }

    /**
     * deletes SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteDomain( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        else{
            return self::$_instance->delete(array( 'sip_domains', $reqData['DomainSid']  ));
        }
    }

    /**
     * shows info on credential lists attached to a SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listMappedCredentialList( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_domains', $reqData['DomainSid'], "CredentialListMappings" ));
        }
    }

    /**
     * maps credentials list to a SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     *      @type string $reqData['CredentialListSid'][The SID of the credential list that you wish to associate with this domain.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function mapCredentialList( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        elseif( !isset($reqData['CredentialListSid']) || is_null( $reqData['CredentialListSid'] )) throw new ZangException("'CredentialListSid' is not set.");
        else{
            $domainSid = $reqData['DomainSid'];
            unset($reqData['DomainSid']);
            return self::$_instance->create(array( 'sip_domains', $domainSid, "CredentialListMappings" ), $reqData);
        }
    }

    /**
     * deletes a credential list mapped to some SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     *      @type string $reqData['CLSid'][Credentials list SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteMappedCredentialList( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        elseif( !isset($reqData['CLSid']) || is_null( $reqData['CLSid'] )) throw new ZangException("'CLSid' is not set.");
        else{
            return self::$_instance->delete(array( 'sip_domains', $reqData['DomainSid'], "CredentialListMappings", $reqData['CLSid'] ));
        }
    }

    /**
     * shows info on IP access control lists attached to a SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function listMappedIpAcls( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        else{
            return self::$_instance->get(array( 'sip_domains', $reqData['DomainSid'], "IpAccessControlListMappings" ));
        }
    }


    /**
     * maps IP access control list to a SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     *      @type string $reqData['IpAccessControlListSid'][The Sid of the IP ACL that you wish to associate with this domain.]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function mapIpAcl( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        elseif( !isset($reqData['IpAccessControlListSid']) || is_null( $reqData['IpAccessControlListSid'] )) throw new ZangException("'IpAccessControlListSid' is not set.");
        else{
            $domainSid = $reqData['DomainSid'];
            unset($reqData['DomainSid']);
            return self::$_instance->create(array( 'sip_domains', $domainSid, "IpAccessControlListMappings" ), $reqData);
        }
    }

    /**
     * detaches an IP access control list from a SIP domain
     * @param $reqData array {
     *      @type string $reqData['DomainSid'][Domain SID]
     *      @type string $reqData['CLSid'][Credentials list SID]
     * }
     * @return Zang_Connector
     * @throws ZangException
     */
    function deleteMappedIpAcl( Array $reqData=array() ){
        if( !isset($reqData['DomainSid']) || is_null( $reqData['DomainSid'] )) throw new ZangException("'DomainSid' is not set.");
        elseif( !isset($reqData['ALSid']) || is_null( $reqData['ALSid'] )) throw new ZangException("'ALSid' is not set.");
        else{
            return self::$_instance->delete(array( 'sip_domains', $reqData['DomainSid'], "IpAccessControlListMappings", $reqData['ALSid'] ));
        }
    }
}