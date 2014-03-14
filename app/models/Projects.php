<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Model;


class Projects extends Models
{

    public $_id;

    public $name;

    public $active;

    public $progress;

    public $start_date;

    public $end_date;

    public $status_date;



public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}