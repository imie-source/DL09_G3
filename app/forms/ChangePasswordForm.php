<?php
namespace Nannyster\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class ChangePasswordForm extends Form
{

    public function initialize()
    {
        // Password
        $password = new Password('password', array(
            'placeholder' => 'Nouveau mot de passe',
            'class' => 'form-control'
        ));

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre mot de passe est obligatoire'
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Votre mot de passe est trop court. Au moins 8 caractères'
            )),
            new Confirmation(array(
                'message' => 'Le mot de passe ne correspond pas avec la confirmation',
                'with' => 'confirmPassword'
            ))
        ));

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword', array(
            'placeholder' => 'Confirmation',
            'class' => 'form-control'
        ));

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'La confirmation du mor de passe est obligatoire'
            ))
        ));

        $this->add($confirmPassword);
    }
}
