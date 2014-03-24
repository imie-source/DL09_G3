<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\View;
use Nannyster\Models\Skills;
use Nannyster\Forms\AddSkillForm;

class SkillsController extends ControllerBase
{

	public function indexAction(){

		$this->tag->prependTitle('Compétences - ');
        $this->view->setVar('activeClass', 'skills');
        $this->view->setVar('breadcrumbs', array(
            'Compétences' => array(
                'last' => true)
        ));
        $this->view->setVar('addSkillForm', new AddSkillForm());
	    $this->assets->addJs('js/fuelux/fuelux.tree.min.js');
	    $this->assets->addJs('js/jquery.raty.js');
	    $this->assets->addJs('js/skills/index.js');
	    $identity = $this->auth->getIdentity();
	    if($this->acl->isAllowed($identity['profile_name'], 'skills', 'delete')){
	    	$this->assets->addJs('js/skills/remove.js');
	    }

	    $skillsToValide = Skills::find(array(array(
			'valide' => 'N')
		));
	    if($skillsToValide){
	    	$parentSkill = array();
			for ($i = 0; $i < sizeof($skillsToValide); $i++) { 
	    		if(isset($skillsToValide[$i]->parent_id)){
					$parentSkill[$i] = Skills::findById(new \MongoId($skillsToValide[$i]->parent_id));
				}
				else{
					$parentSkill[$i] = null;
				}
			}
		}
		$this->view->setVar('skillsToValide', $skillsToValide);
		$this->view->setVar('parentSkill', $parentSkill);
	}

	public function addAction(){
		if($this->request->isPost()){
			$data = $this->request->getPost();
			$skill = new Skills();
			$skill->assign($data);
			if($skill->save()){
				$this->flash->success('La compétence a bien été enregistée');
				return $this->response->redirect('skills');
			}
			else{
				$this->flash->error('Une erreur est survenue lors de l\'enregistrement de la compétence; Veuillez recomencer.');
				return $this->dispatcher->forward(array('controller' => 'skills'));
			}
		}
		else{
			$this->response->redirect('skills');
		}
	}

	public function valideAction($id){
		if($id != null){
			if(self::validateMongoId($id)){
				$skill = Skills::findById(new \MongoId($id));
				$skill->valide = 'Y';
				if($skill->save()){
					$this->flash->success('La compétence '.$skill->name.' a bien été validée!');
					return $this->response->redirect('skills');
				}
				else{
					$this->flash->error('Une erreur est survenue lors de la validation de la compétence. Veuillez recommencer!');
					return $this->response->redirect('skills');
				}
			}
			else{
				$this->flash->error('L\'identifiant de compétence n\'est pas valide! Pirate');
				return $this->response->redirect('skills');
			}
		}
		$this->response->redirect('skills');
	}

	public function deleteAction($id){
		if($id != null){
			if(self::validateMongoId($id)){
				$skill = Skills::findById(new \MongoId($id));
				if($skill->delete()){
					$this->flash->success('La compétence '.$skill->name.' a bien été supprimée!');
					return $this->response->redirect('skills');
				}
				else{
					$this->flash->error('Une erreur est survenue lors de la suppression de la compétence. Veuillez recommencer!');
					return $this->response->redirect('skills');
				}
			}
			else{
				$this->flash->error('L\'identifiant de compétence n\'est pas valide! Pirate');
				return $this->response->redirect('skills');
			}
		}
		$this->response->redirect('skills');
	}

	public function proposeAction(){
		if($this->request->isPost()){
			$data = $this->request->getPost();
			$skill = new Skills();
			$skill->assign($data);
			$skill->valide = 'N';
			if($skill->save()){
				$this->flash->success('La compétence a bien été proposée. Un administrateur doit encore valider cet ajout');
				return $this->response->redirect('skills');
			}
			else{
				$this->flash->error('Une erreur est survenue lors de l\'enregistrement de la compétence; Veuillez recomencer.');
				return $this->dispatcher->forward(array('controller' => 'skills'));
			}
		}
		else{
			$this->response->redirect('skills');
		}
	}

	public function ajaxTreeFinderAction(){
		if($this->request->isPost()){
			if($this->request->isAjax()){
				$this->view->setRenderLevel(View::LEVEL_NO_RENDER);

				$data = $this->request->getPost();

				if($data['id'] == 0){
					$parentId = null;
				}
				else{
					$parentId = $data['id'];
				}

				$skills['status'] = 'OK';
				$skills['data'] = Skills::find(array(array(
					'valide' => 'Y',
					'parent_id' => $parentId)
				));

				for($i = 0; $i < sizeof($skills['data']); $i++){

					$additionalParams = Skills::find(array(array(
						'parent_id' => (string) $skills['data'][$i]->_id,
						'valide' => 'Y')));

					if($additionalParams){

						$skills['data'][$i]->children = count($additionalParams);
						$skills['data'][$i]->additionalParameters = array('id' => (string) $skills['data'][$i]->_id, 'children' => true, 'itemSelected' => true);
					}
					else{
						$skills['data'][$i]->type = 'item';
						$skills['data'][$i]->additionalParameters = array('id' => (string) $skills['data'][$i]->_id, 'children' => true, 'itemSelected' => true);
					}

					$skills['data'][$i]->id = (string) $skills['data'][$i]->_id;
					unset($skills['data'][$i]->_id);
				}

				$this->response->setHeader('Content-type', 'application/json');
	            echo json_encode($skills);
            	return true;
			}
		}

	}

}