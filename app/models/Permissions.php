<?php
namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

/**
 * Permissions
 * Stores the permissions by profile
 */
class Permissions extends Collection
{

    /**
     *
     * @var integer
     */
    public $_id;

    /**
     *
     * @var MongoId
     */
    public $profile_id;

    /**
     *
     * @var string
     */
    public $resource;

    /**
     *
     * @var string
     */
    public $action;

    public function initialize()
    {
        /*$this->belongsTo('profilesId', 'Vokuro\Models\Profiles', 'id', array(
            'alias' => 'profile'
        ));*/
    }
}
