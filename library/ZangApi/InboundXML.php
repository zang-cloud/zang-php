<?php

/** @see ZangException **/
require_once 'ZangException.php';

/** @see Zang_Schemas **/
require_once 'Schemas.php';


require_once 'Helpers.php';

/**
 * 
 * A ZangAPI InboundXML wrapper.
 * 
 * Please consult the online documentation for more details.
 * Online documentation can be found at: http://www.zang.io/docs/api/inboundxml/
 * 
 * --------------------------------------------------------------------------------
 * 
 * @category  ZangApi Wrapper
 * @package   ZangApi
 * @author    Nevio Vesic
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @copyright Avaya Cloud Inc.
 */

class Zang_InboundXML
{
    
    /**
     * InboundXML simple xml element container
     * 
     * @var null|SimpleXmlElement
     */
    protected $element;
    
    /**
     * Current child pointer. Used for nesting validations
     * 
     * @var string|null
     */
    protected $_currentChild = null;


    protected $_xsdSchema = "/schemas/inboundxml.xsd";

    /**
     * Constructs a InboundXML response or request.
     *
     * @param SimpleXmlElement|array $arg:
     *   - the element to wrap
     *   - attributes to add to the element
     *   - if null, initialize an empty element named 'Response'
     */
    public function __construct($arg = null) {
        switch (true) {
            case $arg instanceof SimpleXmlElement:
                $this->element = $arg;
                $this->_currentChild = strtolower($arg->getName());
                break;
            case $arg === null:
                $this->element = new SimpleXmlElement('<Response/>');
                $this->_currentChild = 'response';
                break;
            case is_array($arg):
                $this->element = new SimpleXmlElement('<Response/>');
                $this->_currentChild = 'response';
                foreach ($arg as $name => $value) {
                    $this->_validateAttribute($name, 'response');
                    $this->element->addAttribute($name, $value);
                }
                break;
            default: throw new ZangException('InboundXML Invalid construction argument');
        }
    }
	
    
    /**
     * Converts method calls into InboundXML verbs.
     *
     * @return SimpleXmlElement A SimpleXmlElement
     */
    public function __call($verb, array $args) {

        /** convert verbs input like-this-one to LikeThisOne **/
//        $verb = preg_replace("/[-_]([a-z])/e", "ucfirst('\\1')", ucwords($verb));
        $verb = preg_replace_callback("/[-_]([a-z])/", function($m){return "ucfirst('\\1')";}, ucwords($verb));
        
        /** Let's first go check if the verb exists **/
        $this->_validateVerb(ucfirst($verb));

        /** Let's go validate nesting **/
        $this->_validateNesting(ucfirst($verb));
        
        list($noun, $attrs) = $args + array('', array());
        
        if (is_array($noun)) list($attrs, $noun) = array($noun, '');

        $child = empty($noun)
            ? $this->element->addChild(ucfirst($verb))
            : $this->element->addChild(ucfirst($verb), $noun);
            
        foreach ($attrs as $name => $value) {
            /** Validation of verb attributes **/
            $this->_validateAttribute($name, $verb);
            $child->addAttribute($name, $value);
        }
        return new self($child);
    }

    
    /**
     * Returns the object as XML.
     *
     * @return string The response as an XML string
     */
    public function __toString() {
        $xml = $this->element->asXml();
        $xmlAsString = str_replace(
            '<?xml version="1.0" ?>', 
            '<?xml version="1.0" encoding="UTF-8" ?>', 
            $xml
        );
        $this -> _validateAgainstXSD($xmlAsString);
        return $xmlAsString;
    }
    
    
    /**
     * Validate existance of the verb. Return true if exists, throw exception
     * if fails.
     * 
     * @param  string $verb
     * @throws ZangException
     * @return bool
     */
    private function _validateVerb($verb) {
        $schemas = Zang_Schemas::getInstance();
        
        if(!$schemas->isVerb(ucfirst($verb))) {
            $available_verbs = implode(', ', $schemas->getAvailableVerbs());
            throw new ZangException(
                "Verb '{$verb}' is not a valid InboundXML verb. Available verbs are: '{$available_verbs}'"
            );
        }
        
        return true;
    }
    
    
    /**
     * Validate if previous child allows this verb to be its child.
     * 
     * @param  string  $verb
     * @return boolean
     * @throws ZangException
     */
    private function _validateNesting($verb) {
        $schemas = Zang_Schemas::getInstance();
        
        if(!$schemas->isNestingAllowed(ucfirst($this->_currentChild), ucfirst($verb))) {
            $nestable_verbs = implode(', ', $schemas->getNestableByVerbs(ucfirst($this->_currentChild)));
            $current_verb   = ucfirst($this->_currentChild);
            $next_verb      = ucfirst($verb);
            throw new ZangException(
                "InboundXML element '{$current_verb}' does not support '{$next_verb}' element. The following elements are supported: '{$nestable_verbs}'."
            );
        }
        
        return true;
    }
    
    
    /**
     * Validate if attribute of verb exists. If not, throw exception, otherwise, return true.
     * 
     * @param  string $attr
     * @param  string $verb
     * @return boolean
     * @throws ZangException
     */
    private function _validateAttribute($attr, $verb) {
        $schemas = Zang_Schemas::getInstance();
        
        if(!$schemas->isValidAttribute($attr, ucfirst($verb))) {
            $verb_attribuges = implode(', ', $schemas->getAvailableAttributes(ucfirst($verb)));
            throw new ZangException(
                "Attribute '{$attr}' does not exist for verb '{$verb}'. Available attributes are: '{$verb_attribuges}'"
            );
        }
        return true;
    }


    function libxml_display_error($error)
    {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b>\n";

        return $return;
    }

    function libxml_display_errors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            print $this-> libxml_display_error($error);
        }
        libxml_clear_errors();
    }



    private function _validateAgainstXSD( $xml ){
        // Enable user error handling
        libxml_use_internal_errors(true);

        $domDoc = new DOMDocument();
        $domDoc->loadXML($xml, LIBXML_NOBLANKS);
        if (!$domDoc->schemaValidate(Zang_Helpers::getAppRootPath() . $this -> _xsdSchema )) {
            print '<b>Errors Found!</b>';
            $this -> libxml_display_errors();
            throw new ZangException("InboundXML did not pass validation!");
        }

    }
}
