<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;



class Projects extends Collection
{

    public $_id;
    public $name;
    public $descriptions;
    public $progress;
    public $start_date;
    public $end_date;
    public $status_name;

}