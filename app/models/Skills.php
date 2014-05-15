<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

class Skills extends Collection
{

	public $_id;

	public $id = null;

	public $name;

	public $description = null;

	public $parent_id = null;

	public $type = 'folder';

	public $valide = 'Y';

	public $additionalParameters;

	public $children = 0;
    
    public $created;

    public function assign($data){
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }

    public function beforeValidationOnCreate(){
    	if($this->description == ''){
    		$this->description = null;
    	}
    	if($this->parent_id == ''){
    		$this->parent_id = null;
    	}
        $this->created = time();
    }

}