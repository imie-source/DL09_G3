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

	    $this->assets->addJs('js/fuelux/data/fuelux.tree-sampledata.js');
	    $this->assets->addJs('js/fuelux/fuelux.tree.min.js');
	    $this->assets->addJs('js/skills/index.js');
	}

}