<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\View;

use Phalcon\Mvc\Collection;
use Nannyster\Forms\CreateProjectForm;
use Nannyster\Models\ProjectSkills;
use Nannyster\Models\Projects;
use Nannyster\Models\ProjectsStatus;
use Nannyster\Models\Users;



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
      $project->valide='Y';
      $project->project_master=$this->auth->getId();

          //transforme la variable start_date format fr en array
      $date_start_fr=explode('/',$project->start_date);
          //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
      $start=strtotime($date_start_fr[1]+$date_start_fr[0]+$date_start_fr[2]);
          //transforme la variable start_date format fr en array
      $date_end_fr=explode('/',$project->end_date);
          //recup les cases du array en les concaténant au format de date anglais afin d'appliquer la fonction strtotime, qui fonctione que au format anglais.
      $end=strtotime($date_end_fr[1]+$date_end_fr[0]+$date_end_fr[2]);

      if ($start < $end) {
        if ($project->save()) {
          $this->flash->success('votre projet à bien été enregistré');
          return $this->response->redirect('projects/view/'.$project->_id);
          return true;
        }
        else{
          $this->flash->error('Une erreur de date est survenue lors de l\'enregistrement de votre projet; Veuillez recomencer.');
          $this->response->redirect('projects/create');
          return false;
        }
      }
    
    unset($_POST);   
    $this->flash->error('Une erreur est survenue lors de l\'enregistrement de votre projet; Veuillez recomencer.');
    return $this->response->redirect('projects/create');
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
  $this->assets->addJs('js/projects/create.js');
  $this->assets->addJs('js/jquery.maskedinput.min.js');
  if($this->request->isPost()){
    $data = $this->request->getPost();
    $project = new Projects();
    $project->assign($data);
    $project->project_master=$this->auth->getId();
    if ($project->save()) {
      $this->flash->success('votre projet à bien été proposé, un administrateur doit encore valider cet ajout');
      return $this->response->redirect('projects/view/'.$project->_id);
    }

    $this->flash->error('Une erreur est survenue lors de l\'enregistrement de votre projet; Veuillez recomencer.');
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

    if($id != null){

      if(self::validateMongoId($id)){
        //Retrieve project infos
        $projects = Projects::findById(new \MongoId($id));

        //If viewed project_master is the logged user, update is allowed

        if($projects->project_master == (string)$this->auth->getId()){
          $updateAllowed = true;
        }
        //If not, update is disabled
        else{
          $updateAllowed = false;
        }
      }
      else{
        $this->flash->error('Le projet demandé n\'existe pas!');
        $this->response->redirect('projects/search');
      }
    }
    elseif($this->session->has('auth-identity')){
        //Retrieve user infos
        $project_master = Projects::findById(new \MongoId($this->auth->getId()));
        //Enable update
        $updateAllowed = true;
    }
    //If $id is not defined, we show current logged project
    else{
      $this->flash->error('Le projet demandé n\'existe pas!');
      $this->response->redirect('projects/search');
    }

    //If project exists
    if($projects != false){
     // $progress = progress::findbyId(new \MongoId($projects->progress));
        if($skills){
          for ($i = 0; $i < sizeof($skills); $i++) { 
            $skills[$i]->name = Skills::findById(new \MongoId($skills[$i]->skill_id));
          }
        }
    }

    //Or not, redirecting to dashboard page with an error message
    else{
      $this->flash->error('Le projet demandé n\'existe pas!');
      $this->response->redirect('projects/search');
    }

    //Define usefull js and css if update is allowed
    if($updateAllowed){
      $this->assets->addJs('js/bootstrap-editable.js');
      $this->assets->addCss('css/bootstrap-editable.css');
      $this->assets->addJs('js/x-editable/ace-editable.min.js');
      $this->assets->addJs('js/plupload/plupload.js');
      $this->assets->addJs('js/plupload/plupload.flash.js');
      $this->assets->addJs('js/plupload/plupload.html5.js');
      $this->assets->addJs('js/projects/view.js');
    }


    //Define page title and breadcrumbs
    $this->tag->prependTitle('Projet '.ucwords($projects->name));
    $this->view->setVar('breadcrumbs', array(
        'Projets' => array(
                'controller' => 'projects',
                'action' => 'index'),
        'Projet '.ucwords($projects->name) => array(
            'last' => true)));

    //Pass vars to view
    $this->view->setVar('updateAllowed', $updateAllowed);
    $this->view->setVar('projects', $projects);
    
  }
}