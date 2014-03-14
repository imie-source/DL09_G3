<?php

namespace Nannyster\Controllers;

use Nannyster\Models\Projects;


class ProjectsController extends ControllerBase
{

	public function indexAction(){


    $this->tag->prependTitle('Projets - ');
    $this->view->setVar('activeClass', 'projects');
    $this->view->setVar('breadcrumbs', array(
            'Projets' => array(
                'last' => true)


        ));
    $this->assets->addJs('js/jquery.dataTables.min.js');
    $this->assets->addJs('js/jquery.dataTables.bootstrap.js');
    $this->assets->addJs('js/bootbox.min.js');
    $this->assets->addJs('js/users/manage.js');

    $this->view->setVar('projects', Projects::find());
    
    }
}