<?php

namespace Nannyster\Models;

use Phalcon\Mvc\Collection;

class Skills extends Collection
{

	public $_id;

	public $id = null;

	public $name;

	public $description;

	public $parent_id;

	public $type = 'folder';

	public $valide;

	public $additionalParameters;

}