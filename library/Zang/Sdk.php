<?php

/**
 *
 * A Zang Client wrapper.
 *
 * This class will help you with generating and managing Zang client tokens.
 *
 * --------------------------------------------------------------------------------
 *
 * @category  Zang Wrapper
 * @package   Zang
 * @author    Nevio Vesic <nevio@zang.io>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2012) Zang, Inc. <info@zang.io>
 */

class Zang_Client extends Zang_Related
{

	/**
	 * Singleton instance container
	 * @var self|null
	 */
	protected static $_instance = null;

	/**
	 * All available resources sorted by a application sids. This is used to for example
	 * figure out client_password.
	 * @var array
	 */
	protected static $_resource = array();

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
     * Generate token by application sid. If an error occurs, a Zang Exception will be thrown
     * try/catch block.
	 *
	 * @param  String  $application_sid
	 * @param  Boolean $force_regenerate     If true, regardless of the existing token, it will be regenerated
	 * @return Ambigous <string, multitype:>
	 */
	public function generateToken($application_sid,$client_nickname, $force_regenerate=false) {

		$this->_isApplication($application_sid);

		# In case that token already exists and force_regenerate is false
		if($this->tokenExists($application_sid) && !$force_regenerate) return $this->getToken($application_sid);

		$resource = $this->create(array('Applications', $application_sid, 'Clients', 'Tokens'), array(
			'Nickname'					=> $client_nickname,
			'X-ZANG-HELPER'           => 'PHP',
			'X-ZANG-HELPER-TIMESTAMP'	=> time()
		));

		self::$_resource[$application_sid] = $resource;

		$this->setToken($application_sid, $resource->sid);

		return $resource;
	}


	/**
	 * Set the token for some application regardless of if token is already assigned
	 *
	 * @param  String $application_sid
	 * @param  String $token
	 * @throws Zang_Exception  if application sid is not correct
	 */
	public function setToken($application_sid, $token) {

		$this->_isApplication($application_sid);

		$this->_isTokenValid($token);

		$this->_sdkToken[$application_sid] = $token;

		return true;
	}


	/**
	 * Return client token or NULL if not set
	 *
	 * @return String <string, NULL>
	 * @throws Zang_Exception  if application sid is not correct
	 */
	public function getToken($application_sid) {

		$this->_isApplication($application_sid);

		return isset($this->_sdkToken[$application_sid]) ? $this->_sdkToken[$application_sid] : null;
	}


	public function getPassword($application_sid) {

		$this->_isApplication($application_sid);

		if( array_key_exists($application_sid, self::$_resource) ) {
			if( @self::$_resource[$application_sid]->client_password ) {
				return self::$_resource[$application_sid]->client_password;
			}
		}

		return null;
	}


	/**
	 * Return whenever token is generated and is set within the class.
	 *
	 * @return boolean
	 * @throws Zang_Exception  if application sid is not correct
	 */
	public function tokenExists($application_sid) {

		$this->_isApplication($application_sid);

		return $this->getToken($application_sid) ? true : false;
	}


	public function passwordExists($application_sid) {
		$this->_isApplication($application_sid);
	}


	/**
	 * Check if Application SID is valid
	 *
	 * @param  String  $application_sid
	 * @throws Zang_Exception
	 */
	private function _isApplication($application_sid) {
		if(! is_string($application_sid) || strlen($application_sid) != 34 || substr($application_sid, 0, 2) != "AP" ) {
			throw new Zang_Exception("Provided Zang Application sid is not valid.");
		}
	}

	/**
	 * Check if Application client sid is valid when received
	 *
	 * @param  String  $client_sid
	 * @throws Zang_Exception
	 * @return boolean
	 */
	private function _isTokenValid($client_sid) {
		if(is_null($client_sid) || strlen($client_sid) < 20) {
			throw new Zang_Exception("Specified Zang Application client sid is not valid!");
		}

		return true;
	}
}
