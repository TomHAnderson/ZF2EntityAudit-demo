<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Type extends Form {

    public function __construct() {
        parent::__construct();
        $this->setAttribute("method", "post");
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'id' => "name"
            ),
            "options" => array(
                'label' => 'Name',
            )
        ));

        $this->add(array(
            'name' => 'tag',
            'attributes' => array(
                'type' => 'text',
                'id' => 'tag',
            ),
            "options" => array(
                'label' => 'Tag',
            )
        ));

        $this->add(array(
            'name' => 'revisionComment',
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'revisionComment',
            ),
            "options" => array(
                'label' => 'Revision Comment',
            )
        ));

        $this->add(array(
            'name' => 'save',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-success',
                'value' => 'Create'
            ),
            "options" => array(
                'label' => " ",
            )
        ));
        return $this;
    }

}
