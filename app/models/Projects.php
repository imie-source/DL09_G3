<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;



class Projects extends Collection
{

    public $_id;
    public $name;
    public $nb_users_max;
    public $description;
    public $progress = 0;
    public $start_date;
    public $end_date;
    public $status_name;
    public $valid='N';
    public $project_master;


public function beforeValidationOnCreate(){
	$this->project_master=$this->auth->getId();

}    



public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}