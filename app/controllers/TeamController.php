<?php

namespace Nannyster\Controllers;

class TeamController extends ControllerBase
{
	public function indexAction(){
		$this->tag->prependTitle('La Dream Team - ');
        $this->view->setVar('activeClass', 'team');
        $this->view->setVar('breadcrumbs', array(
            'La Dream Team' => array(
                'last' => true)
        ));
	}
}