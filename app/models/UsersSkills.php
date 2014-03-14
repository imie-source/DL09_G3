<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

class UsersSkills extends Collection
{
	public $_id;

	public $user_id;

	public $skill_id;

	public $name;

	public $rate;

	public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}