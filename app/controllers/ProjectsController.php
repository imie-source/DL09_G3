<?php

namespace Nannyster\Controllers;


use Nannyster\Forms\CreateProjectForm;

use Nannyster\Models\Projects;
use Nannyster\Models\ProjectsStatus;
use Nannyster\Models\Users;



class ProjectsController extends ControllerBase
{


	public function createAction(){

		$this->tag->prependTitle('Proposer un projet - ');
        $this->assets->addJs('js/fuelux/fuelux.spinner.min.js');
        $this->view->setVar('activeClass', 'projects');
        $this->view->setVar('breadcrumbs', array(
    		'Projets' => array(
                'controller' => 'projects',
                'action' => 'index'),
            'Proposer un projet' => array(
                'last' => true)


        ));
		$this->assets->addJs('js/projects/create.js');
		$this->assets->addJs('js/jquery.maskedinput.min.js');
		if($this->request->isPost()){
			$data = $this->request->getPost();
			$project = new Projects();
			$project->assign($data);
			if ($project->save()) {
				$this->flash->success('votre projet à bien été proposé');
				return $this->response->redirect('projects/view/'.$project->_id);
			}

			$this->flash->error('votre projetn\'a pu être proposé!');
			$this->dispatcher->forward(array('controller' => 'projects', 'action' => 'create'));
				}


				$form = new CreateProjectForm();
				$this->view->formCreate = $form;
			}	

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
    $this->view->setVar('users', Users::find());
    $this->view->setVar('status', ProjectsStatus::find());

    }

}