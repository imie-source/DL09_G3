<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\Collection;
use Phalcon\Mvc\View;
use Nannyster\Forms\CreateProjectForm;
use Nannyster\Models\ProjectSkills;
use Nannyster\Models\Projects;
use Nannyster\Models\ProjectStatus;
use Nannyster\Models\Users;
use Nannyster\Models\UsersProjects;



class ProjectsController extends ControllerBase
{

  //method used to add a project if the logged person is more than a simple user 

	public function createAction(){

    $this->tag->prependTitle('Ajouter un projet - ');                                      
    $this->assets->addJs('js/fuelux/fuelux.spinner.min.js');
    $this->view->setVar('activeClass', 'projects');
    $this->view->setVar('breadcrumbs', array(
      'Projets' => array(
        'controller' => 'projects',
        'action' => 'index'),
      'Ajouter un projet' => array(
        'last' => true)


      ));

    $form = new CreateProjectForm();
    $this->view->formCreate = $form;
    $this->assets->addJs('js/projects/create.js');
    $this->assets->addJs('js/jquery.maskedinput.min.js');

    if($this->request->isPost()){
      $data = $this->request->getPost();
      $project = new Projects();
      $project->assign($data);
      $project->valide = 'Y';
      $project->project_master = $this->auth->getId();

          //transforme la variable start_date format fr en array
      $date_start_fr = explode('/',$project->start_date);
          //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
      $start = strtotime($date_start_fr[1].'-'.$date_start_fr[0].'-'.$date_start_fr[2]);
          //transforme la variable start_date format fr en array
      $date_end_fr = explode('/',$project->end_date);
          //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
      $end = strtotime($date_end_fr[1].'-'.$date_end_fr[0].'-'.$date_end_fr[2]);

      if ($start <= $end) {
        if ($project->save()) {
          $this->flash->success('Votre projet à bien été enregistré');
          return $this->response->redirect('projects/view/'.$project->_id);
          return true;
        }  
            $this->flash->error('Une erreur est survenue lors de l\'enregistrement de votre projet; Veuillez recomencer.');
       
        }
           $this->flash->error('Vous ne pouvez pas enregistrer un projet avec une date de début supérieure à la date de fin.');         
    }
}

				

      //method used to propose a project if the logged person is a simple user 

public function proposeAction(){

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

    $form = new CreateProjectForm();
    $this->view->formCreate = $form;
    $this->assets->addJs('js/projects/create.js');
    $this->assets->addJs('js/jquery.maskedinput.min.js');

    if($this->request->isPost()){
      $data = $this->request->getPost();
      $project = new Projects();
      $project->assign($data);
      $project->valide='N';
      $project->project_master=$this->auth->getId();

          //transforme la variable start_date format fr en array
      $date_start_fr=explode('/',$project->start_date);
          //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
      $start=strtotime($date_start_fr[1]+$date_start_fr[0]+$date_start_fr[2]);
          //transforme la variable start_date format fr en array
      $date_end_fr=explode('/',$project->end_date);
          //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
      $end=strtotime($date_end_fr[1]+$date_end_fr[0]+$date_end_fr[2]);

      if ($start <= $end) {
        if ($project->save()) {
          $this->flash->success('Votre projet a bien été proposé. Un administrateur doit encore le valider.');
          return $this->response->redirect('projects/view/'.$project->_id);
          return true;
        }  
        else{
          $this->flash->error('Une erreur est survenue lors de l\'enregistrement de votre projet; Veuillez recomencer.');
        }
      }
      else{
        $this->flash->error('Vous ne pouvez pas proposer un projet avec une date de début supérieure à la date de fin.');   
      }
                 
    }
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
    $this->assets->addJs('js/projects/index.js');

    $this->view->setVar('projects', Projects::find(array(array('valide' => 'Y'))));
    $this->view->setVar('projectsToValide', Projects::find(array(array('valide' => 'N'))));
    $this->view->setVar('users', Users::find());
    $this->view->setVar('status', ProjectStatus::find());
    $this->view->setVar('usersProjects', UsersProjects::find());

    }


  public function viewAction($id = null){   

    $this->assets->addJs('js/jquery.slimscroll.min.js');
    $this->assets->addJs('js/jquery-ui-1.10.3.custom.min.js');
    $this->assets->addJs('js/jquery.ui.touch-punch.min.js');
    $this->assets->addJs('js/bootbox.min.js');
    $this->assets->addJs('js/jquery.hotkeys.min.js');
    $this->assets->addJs('js/bootstrap-wysiwyg.min.js');
    $this->assets->addJs('js/select2.min.js');
    $this->assets->addJs('js/date-time/bootstrap-datepicker.min.js');
    $this->assets->addJs('js/fuelux/fuelux.spinner.min.js');
    $this->assets->addJs('js/jquery.maskedinput.min.js');
    $this->assets->addJs('js/jquery.easy-pie-chart.min.js');
    $this->assets->addJs('js/jquery.cookie.js');
    $this->assets->addJs('js/jquery.slimscroll.min.js');

    if($id != null){

      if($this->request->isPost()){
        $data = $this->request->getPost();

        $project = Projects::findById(new \MongoId($id));
        $project->assign($data);

        //transforme la variable start_date format fr en array
        $date_start_fr=explode('/',$project->start_date);
        //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
        $start=strtotime($date_start_fr[2].'/'.$date_start_fr[1].'/'.$date_start_fr[0]);
        //transforme la variable start_date format fr en array
        $date_end_fr=explode('/',$project->end_date);
        //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
        $end=strtotime($date_end_fr[2].'/'.$date_end_fr[1].'/'.$date_end_fr[0]);
        if ($start <= $end) {
          if($project->save()){
            $this->flash->success('Votre projet a bien été mis à jour!');
          }
          else{
            $this->flash->error('Une erreur est survenue dans la mise à jour de votre projet. Veuillez recommencer.');
          }
        }
        else{
          $this->flash->error('Vous ne pouvez pas saisir une date de départ supérieure à la date de fin. Veuillez recommencer.');
        }
      }

      if(self::validateMongoId($id)){
        //Retrieve project infos
        $project = Projects::findById(new \MongoId($id));

        //If viewed project_master is the logged user, update is allowed

        if($project->project_master == (string)$this->auth->getId()){
          $updateAllowed = true;
        }
        //If not, update is disabled
        else{
          $updateAllowed = false;
        }
      }
      else{
        $this->flash->error('L\'id du projet n\'est pas valide!');
        $this->response->redirect('projects/search');
        return false;
      }
    }
    //If $id is not defined, we show current logged project
    else{
      $this->flash->error('Le projet demandé n\'existe pas!');
      $this->response->redirect('projects');
      return false;
    }

    //If project exists
    if($project != false){
      $userProject = UsersProjects::find(array(array(
        'project_id'=>(string)$project->_id
        ))); 
    }

    //Define usefull js and css if update is allowed
    if($updateAllowed){
      $this->assets->addJs('js/bootstrap-editable.js');
      $this->assets->addCss('css/bootstrap-editable.css');
      $this->assets->addJs('js/x-editable/ace-editable.min.js');
    }
      $this->assets->addJs('js/projects/view.js');
      $this->assets->addJs('js/jquery.easy-pie-chart.min.js');


    //Define page title and breadcrumbs
    $this->tag->prependTitle(ucwords($project->name).' - ');
    $this->view->setVar('breadcrumbs', array(
        'Projets' => array(
                'controller' => 'projects',
                'action' => 'index'),
        ucwords($project->name) => array(
            'last' => true)));

    //Pass vars to view
    $this->view->setVar('status', ProjectStatus::find());
    $this->view->setVar('updateAllowed', $updateAllowed);
    $this->view->setVar('project', $project);
    $this->view->setVar('users', Users::find());
    $this->view->setVar('userProject', $userProject);
    
  }   

  /**
   * Validation d'un projet par un admin
   */
  public function valideAction($id){
    if($id != null){
      if(self::validateMongoId($id)){
        $project = Projects::findById(new \MongoId($id));
        $project->valide = 'Y';
        if($project->save()){
          $this->flash->success('Le projet "'.$project->name.'" a bien été validée!');
          return $this->response->redirect('projects');
        }
        else{
          $this->flash->error('Une erreur est survenue lors de la validation du projet. Veuillez recommencer!');
          return $this->response->redirect('projects');
        }
      }
      else{
        $this->flash->error('L\'identifiant de projet n\'est pas valide! Pirate');
        return $this->response->redirect('projects');
      }
    }
    $this->response->redirect('projects');
  }

  /**
   * Suppression d'une compétence
   */
  public function deleteAction($id){
    if($id != null){
      if(self::validateMongoId($id)){
        $project = Projects::findById(new \MongoId($id));
        if($project->delete()){
          $this->flash->success('Le projet "'.$project->name.'" a bien été supprimée!');
          return $this->response->redirect('projects');
        }
        else{
          $this->flash->error('Une erreur est survenue lors de la suppression du projet. Veuillez recommencer!');
          return $this->response->redirect('projects');
        }
      }
      else{
        $this->flash->error('L\'identifiant du projet n\'est pas valide! Pirate');
        return $this->response->redirect('projects');
      }
    }
    $this->response->redirect('projects');
  }

  public function addUserAction($id = null){
    if($id != null){
      if(self::validateMongoId($id)){
        $userProject = new UsersProjects();
        $userProject->project_id = $id;
        $userProject->user_id = (string) $this->auth->getId();

        if($userProject->save()){
          $this->flash->success('Vous avez bien été ajouté aux participants du projet');
          $this->response->redirect('projects/view/'.$id);
          return true;
        }
      }
      else{
        $this->flash->error('L\'identifiant du projet n\'est pas valide!');
        $this->response->redirect('projects');
        return false;
      }
    }
    else{
      $this->flash->error('ID de projet non valide!!!');
      $this->response->redirect('projects');
      return false;
    }
  }

  public function removeUserAction($id = null){
    if($id != null){
      if(self::validateMongoId($id)){
        $userProject = UsersProjects::find(array(array(
          'user_id' => (string) $this->auth->getId(),
          'project_id' => $id)));
        if($userProject){
          if($userProject[0]->delete()){
            $this->flash->success('Vous avez bien été supprimé des participants du projet');
            $this->response->redirect('projects/view/'.$id);
            return true;
          }
        }
      }
      else{
        $this->flash->error('L\'identifiant du projet n\'est pas valide!');
        $this->response->redirect('projects');
        return false;
      }
    }
    else{
      $this->flash->error('ID de projet non valide!!!');
      $this->response->redirect('projects');
      return false;
    }
  }

  public function masterRemoveUserAction($id = null){
    if($id != null){
      if(self::validateMongoId($id)){
        $userProject = UsersProjects::findbyId(new \MongoId($id));
        if($userProject){
          $project_id = $userProject->project_id;
          if($userProject->delete()){
            $this->flash->success('L\'utilisateur bien été supprimé des participants du projet');
            $this->response->redirect('projects/view/'.$project_id);
            return true;
          }
        }
      }
      else{
        $this->flash->error('L\'identifiant du projet n\'est pas valide!');
        $this->response->redirect('projects');
        return false;
      }
    }
    else{
      $this->flash->error('ID de projet non valide!!!');
      $this->response->redirect('projects');
      return false;
    }
  }

  public function addWikiAction($id){
    if($this->request->isPost()){
      if($this->request->isAjax()){
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $data = $this->request->getPost();

        $project = Projects::findById(new \MongoId($id));

        $project->wiki .= '<p>'.$data['wiki'].'</p>';
        $project->save();
      }
    }
  }
}