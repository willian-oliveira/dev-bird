<?php

namespace SONUser\Form;

use Zend\Form\Form;

/**
 * Esta classe monta o formulario
 */
class Login extends Form {

    /**
     * 
     * @param type $name
     * @param type $options
     */
    function __construct($name = null, $options = array()) {

        parent::__construct('Login', $options);

        $this->setAttribute('method', 'post');

        $email = new \Zend\Form\Element\Text('email');
        $email->setLabel('E-mail: ')
                ->setAttribute('placeholder', 'Entre com o e-mail');
        $this->add($email);

        $password = new \Zend\Form\Element\Password('password');
        $password->setLabel('Password: ')
                ->setAttribute('placeholder', 'Entre com a senha');
        $this->add($password);

        $this->add(array(
            'name' => 'submit',
            'type' => '\Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Autenticar',
                'class' => 'btn-success'
            )
        ));
    }

}
