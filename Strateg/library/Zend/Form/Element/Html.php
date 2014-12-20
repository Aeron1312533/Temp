<?php

class Zend_Form_Element_Html extends Zend_Form_Element_Xhtml {
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formNote';
    public $translateId = NULL;

    public function isValid($value, $context = null) {
        return true;
    }
    
    //translate according to translate element
    public function getValue() {
        $valueFiltered = parent::getValue();
        
        $translator = $this->getTranslator();
        if (null !== $translator) {
            return str_replace($this->translateId, $translator->translate($this->translateId), $valueFiltered);
        }
        
        return $valueFiltered;
    }
}