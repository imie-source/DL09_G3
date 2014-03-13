<?php

namespace Nannyster\Controllers;

class ProjectsController extends ControllerBase
{

	public function manageAction(){

    $this->tag->prependTitle('Manager d\'utilisateurs - ');
    $user = $this->auth->getUser();
    $this->view->setVar('breadcrumbs', array(
            'Administration' => array(
                'controller' => 'administration',
                'action' => 'index'),
            'gestion des utilisateurs' => array(
                'last' => true)
        ));
    $this->assets->addJs('js/jquery.dataTables.min.js');
    $this->assets->addJs('js/jquery.dataTables.bootstrap.js');
    $this->assets->addJs('js/bootbox.min.js');
    $this->assets->addJs('js/users/manage.js');

    $this->view->setVar('users', Users::find());
    $this->view->setVar('profiles', Profiles::find());
    $this->view->setVar('promotions', Promotions::find());

    
    }
}