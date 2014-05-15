<?php

namespace Nannyster\Controllers;

use Nannyster\Models\Projects;
use Nannyster\Models\Users;
use Nannyster\Models\UsersProjects;
use Nannyster\Models\Skills;

class DashboardController extends ControllerBase
{

    public function indexAction()
    {


        $this->tag->prependTitle('Projets - ');
        $this->view->setVar('activeClass', 'dashboard');
        $this->view->setVar('breadcrumbs', array(
            'Tableau de bord' => array(
                'last' => true)
        ));
        $this->assets->addJs('js/jquery.dataTables.min.js');
        $this->assets->addJs('js/jquery.dataTables.bootstrap.js');
        $this->assets->addJs('js/bootbox.min.js');
        $this->assets->addJs('js/projects/index.js');

        $this->view->setVar('projects', Projects::find(array(
                    'limit' => 10,
                    'sort' => array('created' => -1)
        )));
        $this->view->setVar('users', Users::find());
        $this->view->setVar('usersProjects', UsersProjects::find(array(
                    'user_id' => $this->auth->getId()
        )));
        $this->view->setVar('skills', Skills::find(array(
                    'limit' => 10,
                    'sort' => array('created' => -1)
        )));
    }

}
