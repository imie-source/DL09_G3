<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;
use Nannyster\Auth\Auth;

class Projects extends Collection
{

    public $_id;
    public $name;
    public $nb_users_max;
    public $description;
    public $progress = 0;
    public $start_date;
    public $end_date;
    public $status_name = 'En attente de participants';
    public $valide='N';
    public $project_master;
    public $wiki = null;


public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}