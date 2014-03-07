<?php
namespace Nannyster\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class LoginForm extends Form
{

    public function initialize()
    {
        // Email
        $login = new Text('login', array(
            'placeholder' => 'Login',
            'class' => 'form-control'
        ));
        $login->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre login est requis'
            ))
        ));
        $this->add($login);

        // Password
        $password = new Password('password', array(
            'placeholder' => 'Mot de passe',
            'class' => 'form-control'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'Votre mot de passe est requis'
        )));
        $this->add($password);

        // Remember
        $remember = new Check('remember', array(
            'value' => 'yes',
            'class' => 'ace'
        ));
        $remember->setLabel('Se souvenir de moi!');
        $this->add($remember);

        $this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
    }
}
