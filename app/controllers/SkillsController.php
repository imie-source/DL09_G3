<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\View;
use Nannyster\Models\Skills;
use Nannyster\Forms\AddSkillForm;

class SkillsController extends ControllerBase
{

	public function indexAction(){

		//titre, active class de la sidebar et breadcrumbs
		$this->tag->prependTitle('Compétences - ');
        $this->view->setVar('activeClass', 'skills');
        $this->view->setVar('breadcrumbs', array(
            'Compétences' => array(
                'last' => true)
        ));

        //formulaire d'ajout de compétence
        $this->view->setVar('addSkillForm', new AddSkillForm());

        //ajout des javascripts spécifiques
	    $this->assets->addJs('js/fuelux/fuelux.tree.min.js');
	    $this->assets->addJs('js/jquery.raty.js');
    	$this->assets->addJs('js/bootbox.min.js');
	    $this->assets->addJs('js/skills/index.js');
	    $identity = $this->auth->getIdentity();

	    //ajout d'un js spécifique si l'utilisateur est autorisé à supprimer des compétences
	    if($this->acl->isAllowed($identity['profile_name'], 'skills', 'delete')){
	    	$this->assets->addJs('js/skills/remove.js');
	    }

	    //Traitement de compétences à valider
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

	/**
	 * Traitement de l'ajout d'une compétence
	 */
	public function addAction(){
		//Si la requete est post, on instancie une nouvelle compétence et on l'hydrate
		if($this->request->isPost()){
			$data = $this->request->getPost();
			$skill = new Skills();
			$skill->assign($data);

			//Si la compétence a pu être enregistrée
			if($skill->save()){
				$this->flash->success('La compétence a bien été enregistée');
				return $this->response->redirect('skills');
			}

			//sinon
			else{
				$this->flash->error('Une erreur est survenue lors de l\'enregistrement de la compétence; Veuillez recomencer.');
				return $this->dispatcher->forward(array('controller' => 'skills'));
			}
		}
		//si la requete n'est pas post
		else{
			$this->response->redirect('skills');
		}
	}

	/**
	 * Validation d'une compétence par un admin
	 */
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

	/**
	 * Suppression d'une compétence
	 */
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

	/**
	 * Proposer une compétence
	 */
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

	/**
	 * Méthode appelée en ajax pour construire l'arbre de compétence. Renvoyée en json
	 */
	public function ajaxTreeFinderAction(){
		//Si la requete et post et ajax
		if($this->request->isPost()){
			if($this->request->isAjax()){
				//On détermine que la vuen'aura pas template ni de layou
				$this->view->setRenderLevel(View::LEVEL_NO_RENDER);

				$data = $this->request->getPost();

				//On définie le parentId avec celui de la compétence cliquée ou à null si la compétence n'a pas de parent
				if($data['id'] == 0){
					$parentId = null;
				}
				else{
					$parentId = $data['id'];
				}

				//Début de construction du tableau qui sera retourné
				$skills['status'] = 'OK';

				//On récupère toutes les compétences validées dont la parent_id correspond à celui demandé
				$skills['data'] = Skills::find(array(array(
					'valide' => 'Y',
					'parent_id' => $parentId)
				));

				//Pour chaque compétence retournée
				for($i = 0; $i < sizeof($skills['data']); $i++){

					//On récupère le nombre de compétences enfants
					$additionalParams = Skills::find(array(array(
						'parent_id' => (string) $skills['data'][$i]->_id,
						'valide' => 'Y')));

					//On ajoute ce nombre dans le tableau à retourner
					if($additionalParams){

						$skills['data'][$i]->children = count($additionalParams);
						$skills['data'][$i]->additionalParameters = array('id' => (string) $skills['data'][$i]->_id, 'children' => true, 'itemSelected' => true);
					}

					//Ou pas
					else{
						$skills['data'][$i]->type = 'item';
						$skills['data'][$i]->additionalParameters = array('id' => (string) $skills['data'][$i]->_id, 'children' => true, 'itemSelected' => true);
					}

					//On enregistre l'objet _id de la compétence en string dans id pour le traitement javascript
					$skills['data'][$i]->id = (string) $skills['data'][$i]->_id;

					//on détruit l'objet _id
					unset($skills['data'][$i]->_id);
				}

				//On définit le header de la réponse
				$this->response->setHeader('Content-type', 'application/json');

				//On encode le tableau en json
	            echo json_encode($skills);
            	return true;

            	//Et voila
			}
		}

	}

}