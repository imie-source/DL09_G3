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
use Nannyster\Models\Schools;
use Nannyster\Validator\UniqueValidator;

class AddAdminForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        //Schools
        $schools = Schools::find();
        
        $school = new Select('school_id', Schools::returnArrayForSelect($schools), array(
            'useEmpty' => true,
            'emptyText' => 'Ecole ...',
            'emptyValue' => '',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $school->addValidators(array(
            new PresenceOf(array(
                'message' => 'L\'école est obligatoire'
            ))
        ));
        $this->add($school);

        //Surname
        $surname = new Text('surname', array(
            'placeholder' => 'NOM',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $surname->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le nom est obligatoire'
            ))
        ));
        $this->add($surname);

        //Login
        $login = new Text('login', array(
            'placeholder' => 'Login',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $login->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le login est obligatoire'
            ))
        ));
        $this->add($login);

        //name
        $name = new Text('name', array(
            'placeholder' => 'Prénom',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Le prénom est obligatoire'
            ))
        ));
        $this->add($name);

        //dob
        $dob = new Text('date_birth', array(
            'placeholder' => 'Date de Naissance',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $this->add($dob);

        //address
        $address = new Text('address', array(
            'placeholder' => 'Adresse',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $this->add($address);

        //zipcode
        $zipcode = new Text('zipcode', array(
            'placeholder' => 'Code Postal',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $this->add($zipcode);

        //city
        $city = new Text('city', array(
            'placeholder' => 'Ville',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $this->add($city);

        //mobile
        $mobile = new Text('mobile', array(
            'placeholder' => 'Mobile',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $this->add($mobile);

        // Email
        $email = new Text('email', array(
            'placeholder' => 'Email',
            'class' => 'col-xs-10 col-sm-5'
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'L\'email est obligatoire'
            )),
            new Email(array(
                'message' => 'L\'email n\'est pas valide'
            )),
            new UniqueValidator(array(
                'field' => 'email',
                'message' => 'L\'email est déjà associé à un compte SPASM',
                'model' => 'Users'
            ))
        ));
        $this->add($email);
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
