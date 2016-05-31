<?php

/** @see Zang_Exception **/
require_once 'Exception.php';

/**
 * 
 * A connector class. Class where Zang response is being "organized" for usage.
 * From here you can get records, attributes, and request header data.
 * 
 * --------------------------------------------------------------------------------
 * 
 * @category  Zang Wrapper
 * @package   Zang
 * @author    Nevio Vesic <nevio@zang.io>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2012) Zang, Inc. <support@zang.io>
 */

class Zang_Connector
{
    /**
     * All the curl data passed by Zang_Related class
     * 
     * @var array
     */
    protected $curl_data = array();
    
    
    /**
     * In case of FALSE, decoded response will be object.
     * In case of TRUE, decoded response will be array.
     * 
     * @var bool
     */
    protected $response_association = false;
    
    /**
     * Current component used for internal retrival of response items
     * 
     * @var string|null
     */
    protected $_component       = null;

    
    /**
     * @param string $curl_data
     * @param bool   $response_association
     */
    function __construct($curl_data, $response_association = false, $component=null) {
        $this->curl_data = $curl_data;
        $this->response_association = $response_association;
        $this->_component = $component;
        
        if($this->curl_data['errno'] > 0) {
            $errno = $this->curl_data['errno'];
            $error = $this->curl_data['error'];
            throw new Zang_Exception("Curl returned error with code '{$errno}' and message '{$error}'");
        } else {
            $http_code = $this->curl_data['info']['http_code'];
            if($http_code != 200) {
                $data = trim($this->curl_data['exec']);
                if(substr($data, 0, 5) == '<?xml') {
                    $xml_data = simplexml_load_string($data);
                    $error_code = isset($xml_data->RestException->Status) ? (string)$xml_data->RestException->Status : 500;
                    $error_message = isset($xml_data->RestException->Message) ? (string)$xml_data->RestException->Message : 'Internal wrapper error';
                } else {
                    $json_data = json_decode(trim($this->curl_data['exec']), false);
                    if(json_last_error() > 0) {
                        $error_code = 500;
                        $error_message = $this->_validateJSON();
                    }
                    else {
                        $error_code = isset($json_data->status) ? $json_data->status : 500;
                        $error_message = isset($json_data->message) ? $json_data->message : 'Internal wrapper error';
                    }
                }

                
                throw new Zang_Exception(
                    "An error occured while querying Zang with the message '{$error_message}' and the error code '{$error_code}'"
                );
            }
        }
        
        $this->_decodeJSON();
    }
    
    
    /**
     * Get response object key details if exists, otherwise return null
     * 
     * @param  string      $name
     * @return mixed|null 
     */
    function __get($name) {
        if($this->response_association == false)
            return isset($this->curl_data['response']->$name) ?
            $this->curl_data['response']->$name : null;
        else
            return isset($this->curl_data['response'][$name]) ?
            $this->curl_data['response'][$name] : null;
    }

    
    /**
     * Returning JSON response as string
     * 
     * @return string
     */
    function __toString() {
        return print_r($this->curl_data['response'], true);
    }
    
    /**
     * Get the attribute of an object. If attribute doesn't exist Zang_Exception
     * will be raised. If attribute exists but it's not set,
     * then default_value will be returned. Default is null
     * 
     * @param  string  $key
     * @param  mixed   $default_value
     * @return mixed
     * @throws Zang_Exception 
     */
    function attr($key, $default_value=null) {
        $schemas = Zang_Schemas::getInstance();
        
        if(!$schemas->isPagingProperty($key)) {
            $available_attrs = implode(", ", $schemas->getPagingProperties());
            throw new Zang_Exception(
                "Attribute you've requested '{$key}' cannot be found. Available attributes are: '{$available_attrs}'"
            );
        }
        if($this->response_association == false)
            return isset($this->curl_data['response']->$key) ? $this->curl_data['response']->$key : $default_value;
        else
            return isset($this->curl_data['response'][$key]) ? $this->curl_data['response'][$key] : $default_value;
    }
    
    
    /**
     * Returning full response as-is
     * 
     * @return stdclass|array 
     */
    function getResponse() { return $this->curl_data['response']; }
    
    
    /**
     * Return all the items of current Zang request. Actual response will be
     * auto fetched based on requested component key
     * 
     * @return object|array
     */
    function items($access_key=null) {
        $component = is_null($access_key) ? $this->_component : $access_key;
        if($this->response_association == false)
            return isset($this->curl_data['response']->$component) ? $this->curl_data['response']->$component : array();
        else
            return isset($this->curl_data['response'][$component]) ? $this->curl_data['response'][$component] : array();
    }
    
    
    /**
     * Decode returned JSON object and throw error in case of a decoding failure.
     * 
     * @throws Zang_Exception 
     * @return Void
     */
    private function _decodeJSON() {
        $result = json_decode(trim($this->curl_data['exec']), $this->response_association);
        
        $error = '';
       

        # JSON will be validated only if you use PHP 5 >= 5.3.0
	if( floatval(phpversion()) < 5.3) {
            if(function_exists('json_last_error')) {
                
                $error  = $this->_validateJSON();
                
                if (!empty($error)) {
                    throw new Zang_Exception('JSON Error: '.$error);
                }
            }
	}
        
        
        $this->curl_data['response'] = $result;
    }
    
    
    /**
     * Get last validation error if exists
     * 
     * @return string 
     */
    private function _validateJSON() {
        switch(json_last_error()) {
            case JSON_ERROR_DEPTH:
                $error =  ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = ' - Unexpected control character found';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error  = ' - Invalid or Malformed JSON';
                break;
            case JSON_ERROR_SYNTAX:
                $error = ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_NONE:
            default:
                $error = '';              
        }
        
        return $error;
    }
}
