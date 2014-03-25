<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

class UsersProjects extends Collection
{

	public $_id;
	public $project_id;
	public $user_id;
	public $is_valid = 'n';

	

    public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = strtolower($v);
        }
    }

}