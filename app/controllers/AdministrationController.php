<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\Collection;

class AdministrationController extends ControllerBase
{
	public function indexAction(){

        $this->tag->prependTitle('Administration - ');
        $this->view->setVar('activeClass', 'administration');
        $this->view->setVar('breadcrumbs', array(
            'Administration' => array(
                'last' => true)
        ));
	}
}