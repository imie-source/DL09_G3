<?php
namespace Nannyster\Models;


/**
 * EmailConfirmations
 * Stores the reset password codes and their evolution
 */
class EmailConfirmations extends \Phalcon\Mvc\Collection
{

    /**
     *
     * @var integer
     */
    public $_id;

    /**
     *
     * @var varchar
     */
    public $user_id;

    /**
     *
     * @var varchar
     */
    public $code;

    /**
     *
     * @var integer
     */
    public $created;

    /**
     *
     * @var integer
     */
    public $modified;

    /**
     *
     * @var char
     */
    public $confirmed;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->created = time();

        // Generate a random confirmation code
        $this->code = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

        // Set status to non-confirmed
        $this->confirmed = 'N';
    }

    /**
     * Sets the timestamp before update the confirmation
     */
    public function beforeValidationOnUpdate()
    {
        // Timestamp the confirmaton
        $this->modified = time();
    }

    /**
     * Send a confirmation e-mail to the user after create the account
     */
    public function afterCreate()
    {
        $user = Users::findById($this->user_id);
        $this->getDI()
            ->getMail()
            ->send(array(
            $user->email => $user->firstname.' '.$user->surname
        ), "Veuillez confirmer votre email", 'confirmation', array(
            'confirmUrl' => '/confirm/' . $this->code . '/' . $user->email
        ));
    }

    public function initialize()
    {
        /*$this->belongsTo('usersId', 'Vokuro\Models\Users', 'id', array(
            'alias' => 'user'
        ));*/
    }
}
