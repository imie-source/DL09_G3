<?php

namespace Nannyster\Models;

use Nannyster\Models\Profiles;

class Users extends \Phalcon\Mvc\Collection
{
    public $_id;
    public $login;
    public $password;
    public $surname;
    public $name;
    public $date_birth;
    public $address;
    public $zipcode;
    public $city;
    public $email;
    public $mobile;
    public $available;
    public $about;
    public $profile_id;
    public $promotion_id;
    public $private_informations = 'N';
    public $first_connection = 'Y';
    public $email_notification = 'Y';
    public $avatar = 'default.jpg';

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        $this->profile_id = Profiles::find(array(array(
            'name' => 'Utilisateur')
        ));
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {

    }

    public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = strtolower($v);
        }
    }
     
}
