<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

class Profiles extends Collection
{
    
    /**
     *
     * @var datetime
     */
    public $_id;

    /**
     *
     * @var datetime
     */
    public $name;

    /**
     *
     * @var string
     */
    public $active;

    /**
     *
     * @var string
     */
    public $style;

    public static function returnArrayForSelect($obj){
        $val = new Collection;
        foreach ($obj as $v) {
            $val->_id = $v['_id'];
            $k = $val->getId();
            $array[(string)$k] = $v['name'];
        }
    	return $array;
    }
}