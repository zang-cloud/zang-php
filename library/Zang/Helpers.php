<?php

/**
 * 
 * Zang common helpers such as filtering numbers over E.164.
 * 
 * --------------------------------------------------------------------------------
 * 
 * @category  Zang Wrapper
 * @package   Zang
 * @author    Nevio Vesic <nevio@zang.io>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright (2012) Zang, Inc. <support@zang.io>
 */

class Zang_Helpers
{
    
    public static function filter_e164($value) {
        $value = preg_replace("/[^\+0-9]/","", trim($value));
        
        if (preg_match("/^\+[1-9][0-9]{5,20}$/",$value)) //it's already in e164 with the +
            $e164 = $value;
        elseif (preg_match("/^1[2-9][0-9]{9}$/",$value)) //it's a North American number
            $e164 = "+".$value;
        elseif (preg_match("/^[2-9][0-9]{9}$/",$value)) //assume it's a North American number w/o country code
            $e164 = "+1".$value;
        elseif (preg_match("/^011[2-9][0-9]{5,20}$/",$value)) //it's an international number with leading 011
            $e164 = preg_replace("/^011/","+",$value);
        elseif (preg_match("/^[2-9][0-9]{5,20}$/",$value)) //it must be an international number with no + or 011 prefix
            $e164 = "+".$value;
        else
            return "";

        return $e164;
    } 
    
}