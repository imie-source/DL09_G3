<?php
namespace Nannyster\Models;


/**
 * FailedLogins
 * This model registers unsuccessfull logins registered and non-registered users have made
 */
class FailedLogins extends \Phalcon\Mvc\Collection
{

    /**
     *
     * @var integer
     */
    public $_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $ip_address;

    /**
     *
     * @var integer
     */
    public $attempted;

    public function initialize()
    {
        /*$this->belongsTo('usersId', 'Vokuro\Models\Users', 'id', array(
            'alias' => 'user'
        ));*/
    }
}
