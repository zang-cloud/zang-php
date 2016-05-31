<?php

/**
 * 
 * A Zang Connect wrapper.
 * 
 * This class will help you with usage of Zang Connect
 * 
 * --------------------------------------------------------------------------------
 * 
 * @category  Zang Wrapper
 * @package   Zang
 * @author    Nevio Vesic <nevio@zang.io>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2012) Zang, Inc. <info@zang.io>
 */

class Zang_Connect extends Zang_Related
{
	/**
	 * Singleton instance container
	 * @var self|null
	 */
	protected static $_instance = null;
	
	/**
	 * Status of the connect. If disabled, connect credentials will be entirely
	 * ignored
	 * 
	 * @var boolean
	 */
	protected static $_status   = true;
	
	
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
	
	
	/**
	 * Get Zang Connect Authorize URL
	 * 
	 * @param string $connect_sid
	 * @return string
	 */
	public function getConnectUrl($connect_sid) {
		if(strlen($connect_sid) != 34) {
			new Zang_Exception("Please provide valid Connect SID in order to generate Connect URL");
		}
		
		return 'http://www.zang.io/connect/authorize/' . $connect_sid;
	}
	
	
	/**
	 * Set Zang Connect client credentials
	 * 
	 * @param string $connect_sid
	 * @param string $access_key
	 * @param string $access_token
	 */
	public function setCredentials($connect_sid, $access_key, $access_token) {
		
		if(strlen($connect_sid) != 34) {
			new Zang_Exception("Please provide valid Connect SID in order to set connect credentials!");
		}
		
		if(strlen($access_key) != 34) {
			new Zang_Exception("Please provide valid AccessKey in order to set connect credentials!");
		}
		
		if(strlen($access_token) != 34) {
			new Zang_Exception("Please provide valid AccessToken in order to set connect credentials!");
		}
		
		self::$_connectHeaders[] = "CONNECT-ACCESS-SID: {$connect_sid}";
		self::$_connectHeaders[] = "CONNECT-ACCESS-KEY: {$access_key}";
		self::$_connectHeaders[] = "CONNECT-ACCESS-TOKEN: {$access_token}";
	}
	
	
	/**
	 * Enable connect client
	 * 
	 * @return Void
	 */
	public function enable() { self::$_status = true; }
	
	
	/**
	 * Disable connect client
	 * 
	 * @return Void
	 */
	public function disable(){ self::$_status = false; }
	
	
	/**
	 * Get connect client status
	 * 
	 * @return boolean
	 */
	public function getStatus() { return self::$_status; }
}