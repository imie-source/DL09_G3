<?php
namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

/**
 * ResetPasswords
 * Stores the reset password codes and their evolution
 */
class ResetPasswords extends Collection
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
    public $code;

    /**
     *
     * @var integer
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $modified_at;

    /**
     *
     * @var string
     */
    public $reset;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->created_at = time();

        // Generate a random confirmation code
        $this->code = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

        // Set status to non-confirmed
        $this->reset = 'N';
    }

    /**
     * Sets the timestamp before update the confirmation
     */
    public function beforeValidationOnUpdate()
    {
        // Timestamp the confirmaton
        $this->modified_at = time();
    }

    /**
     * Send an e-mail to users allowing him/her to reset his/her password
     */
    public function afterCreate()
    {
        $user = Users::findById($this->user_id);
        $this->getDI()
            ->getMail()
            ->send(array(
            $user->email => $user->firstname.' '.$user->surname
        ), "Réinitialisation de votre mot de passe", 'reset', array(
            'resetUrl' => '/reset-password/' . $this->code . '/' . $user->email
        ));
    }

    public function initialize()
    {
        /*$this->belongsTo('usersId', 'Vokuro\Models\Users', 'id', array(
            'alias' => 'user'
        ));*/
    }
}
