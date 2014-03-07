<?php
namespace Nannyster\Models;

use Phalcon\Mvc\Model;

/**
 * PasswordChanges
 * Register when a user changes his/her password
 */
class PasswordChanges extends Model
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
    public $users_id;

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

    /**
     *
     * @var integer
     */
    public $created_at;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->created_at = time();
    }

    public function initialize()
    {
        /*$this->belongsTo('usersId', 'Vokuro\Models\Users', 'id', array(
            'alias' => 'user'
        ));*/
    }
}
