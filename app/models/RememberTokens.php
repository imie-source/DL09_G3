<?php
namespace Nannyster\Models;


/**
 * RememberTokens
 * Stores the remember me tokens
 */
class RememberTokens extends \Phalcon\Mvc\Collection
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
    public $token;

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
