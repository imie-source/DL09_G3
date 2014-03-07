<?php
namespace Nannyster\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class ForgotPasswordForm extends Form
{

    public function initialize()
    {
        $email = new Text('email', array(
            'placeholder' => 'Email',
            'class' => 'form-control'
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre email est requis'
            )),
            new Email(array(
                'message' => 'Votre email n\'est pas valide'
            ))
        ));
        $this->add($email);
    }
}
