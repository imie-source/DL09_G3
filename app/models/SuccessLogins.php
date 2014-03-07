<?php
namespace Nannyster\Models;


/**
 * SuccessLogins
 * This model registers successfull logins registered users have made
 */
class SuccessLogins extends \Phalcon\Mvc\Collection
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
     * @var string
     */
    public $user_agent;

    public function initialize()
    {
        /*$this->belongsTo('usersId', 'Vokuro\Models\Users', 'id', array(
            'alias' => 'user'
        ));*/
    }
}
