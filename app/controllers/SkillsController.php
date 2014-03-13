<?php

namespace Nannyster\Controllers;

class SkillsController extends ControllerBase
{

	public function indexAction(){

		$this->tag->prependTitle('Compétences - ');
        $this->view->setVar('activeClass', 'skills');
        $this->view->setVar('breadcrumbs', array(
            'Compétences' => array(
                'last' => true)
        ));

	}

}