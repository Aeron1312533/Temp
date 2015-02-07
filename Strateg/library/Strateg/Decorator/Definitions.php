<?php

class Strateg_Decorator_Definitions {
    // adds the <div> to the first button
    public static function openButtonDecorators() {
        return array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'class' => 'element', 'openOnly'=>true))
        );
    }

    // just outputs a button no decorator
    public static function noButtonDecorators() {
        return array('ViewHelper');
    }

    //closes the </div> on the last button.
    public static function closeButtonDecorators() {
        return array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'class' => 'element', 'closeOnly'=>true))
        );
    }
    
    public static function hiddenDecorators() {
         return array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'class' => 'hidden-element'))
        );       
    }

}
