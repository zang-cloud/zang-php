<?php

if( floatval(phpversion()) < 5.2) {
    trigger_error(sprintf(
        "Your PHP version %s is not valid. In order to run Zang helper you will need to have at least PHP 5.2 or above.", 
         phpversion() 
    ));
}


/** @see Zang_Exception */
require_once 'Zang/Exception.php';

/** @see Zang_Schemas */
require_once 'Zang/Schemas.php';

/** @see Zang_InboundXML **/
require_once 'Zang/InboundXML.php';

/** @see Zang_Helpers **/
require_once 'Zang/Helpers.php';

/** @see Zang_Connector **/
require_once 'Zang/Connector.php';

/** @see Zang_Related **/
require_once 'Zang/Related.php';

/** @see Zang_Client **/
require_once 'Zang/Sdk.php';

/** @see Zang_Client **/
require_once 'Zang/Connect.php';

/**
 * 
 * Zang singleton instance to the wrapper
 * 
 * --------------------------------------------------------------------------------
 * 
 * @category  Zang Wrapper
 * @package   Zang
 * @author    Nevio Vesic <nevio@zang.io>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2012) Zang, Inc. <info@zang.io>
 */

final class Zang extends Zang_Related
{
    /**
     * Singleton instance container
     * @var self|null 
     */
    protected static $_instance = null;
    
    /**
     * Singleton access method to Zang. This is THE ONLY PROPER WAY to
     * access the Zang wrapper!
     * 
     * @return self
     */
    static function getInstance() {
        if(is_null(self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }
}
