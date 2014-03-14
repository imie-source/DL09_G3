<?php

namespace Nannyster\Controllers;

use Nannyster\Forms\CreateProjectForm;

class ProjectsController extends ControllerBase
{

	public function indexAction()
	{

	}

	public function createAction(){
		$this->assets->addJs('js/projects/create.js');
		$this->assets->addJs('js/jquery.maskedinput.min.js');
		if($this->request->isPost()){
			$data = $this->request->getPost();
			$project = new Project;
			$project->assign($data);
			if ($project->save()) {
				$this->flash->success('votre projet à bien été ajouté');
				return $this->response->redirect('project/view/'.$project->_id);
			}

			$this->flash->error('L\'utilisateur n\'existe pas!');
			$this->dispatcher->forward(array('controller' => 'project', 'action' => 'create'));
				}


				$form = new CreateProjectForm();
				$this->view->formCreate = $form;
			}	
}