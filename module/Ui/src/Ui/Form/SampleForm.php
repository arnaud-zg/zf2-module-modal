<?php

namespace Ui\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\I18n\Translator;

class SampleForm extends Form {

    public function __construct() {
        parent::__construct('sample');

        $translate = new Translator\Translator();
        $this->setInputFilter($subFilter = new InputFilter());
                
        /**    submit     * */
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => $translate->translate("Sample Modal"),
                'class' => 'ui primary button  smodal',
            ),
        ));
    }

}
