<?php
namespace Nannyster\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation;
use Phalcon\Mvc\Collection;
use Nannyster\Models\Profiles;
use Nannyster\Validator\UniqueValidator;

class SignUpForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        //User profile
        $profiles = Profiles::find(array(array(
            'active' => 'Y',
            'visible' => 'Y'),
            'fields' => array(
                '_id',
                'name')
        ));
        $profile = new Select('profile_id', Profiles::returnArrayForSelect($profiles), array(
            'useEmpty' => true,
            'emptyText' => 'Vous êtes ...',
            'emptyValue' => '',
            'class' => 'form-control'
        ));
        $profile->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le type de compte est obligatoire'
            ))
        ));
        $this->add($profile);

        //User civility
        $civility = new Select('civility', array('Mme' => 'Mme', 'Mr' => 'Mr'), array(
            'useEmpty' => true,
            'emptyText' => 'Votre civilité ...',
            'emptyValue' => '',
            'class' => 'form-control'));
        $civility->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre civilité est obligatoire'
            ))
        ));
        $this->add($civility);

        //Surname
        $surname = new Text('surname', array(
            'placeholder' => 'NOM',
            'class' => 'form-control'
        ));
        $surname->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre nom est obligatoire'
            ))
        ));
        $this->add($surname);

        //Wedding surname
        $wedding_surname = new Text('wedding_surname', array(
            'placeholder' => 'NOM MARITAL',
            'class' => 'form-control hidden'
        ));
        $this->add($wedding_surname);

        //Firstname
        $firstname = new Text('firstname', array(
            'placeholder' => 'Prénom',
            'class' => 'form-control'
        ));
        $firstname->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre prénom est obligatoire'
            ))
        ));
        $this->add($firstname);

        // Email
        $email = new Text('email', array(
            'placeholder' => 'Email',
            'class' => 'form-control'
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre email est obligatoire'
            )),
            new Email(array(
                'message' => 'Votre email n\'est pas valide'
            )),
            new UniqueValidator(array(
                'field' => 'email',
                'message' => 'Votre email est déjà associé à un compte Nannyster',
                'model' => 'Users'
            ))
        ));
        $this->add($email);

        // Password
        $password = new Password('password', array(
            'placeholder' => 'Mot de passe',
            'class' => 'form-control'
        ));
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Votre mot de passe est obligatoire'
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Votre mot de passe est trop petit. Au moins 8 caractères'
            )),
            new Confirmation(array(
                'message' => 'Les mots de passe ne correspondent pas',
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
                'message' => 'La confirmation du mot de passe est obligatoire'
            ))
        ));
        $this->add($confirmPassword);

        // Terms
        $terms = new Check('terms', array(
            'value' => 'yes'
        ));
        $terms->addValidator(new Identical(array(
            'value' => 'yes',
            'message' => 'Vous devez accepter les conditions d\'utilisations'
        )));

        $this->add($terms);

        // CSRF
        $csrfSignUp = new Hidden('csrfSignUp');
        $csrfSignUp->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        )));
        $this->add($csrfSignUp);

        // Sign Up
        $submit = new Submit('submit', array(
            'value' => 'S\'enregistrer',
            'class' => 'width-65 pull-right btn btn-sm btn-success'
        ));
        $this->add($submit);
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
