<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

class Schools extends Collection
{

	public $_id;
	public $name;

	public static function returnArrayForSelect($obj){
        $val = new Collection;
        foreach ($obj as $v) {
            $k = (string) $v->_id;
            $array[(string)$k] = ucwords($v->name);
        }
    	return $array;
    }

}