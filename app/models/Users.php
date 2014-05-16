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
    public $available = 'y';
    public $profile_id;
    public $promotion_id;
    public $ecole_id;
    public $private_informations = 'y';
    public $first_connection = 'y';
    public $avatar = 'default.jpg';

    public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = strtolower($v);
        }
    }
     
}
