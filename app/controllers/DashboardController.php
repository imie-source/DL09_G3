<?php

namespace Nannyster\Controllers;

class DashboardController extends ControllerBase
{

    public function indexAction()
    {
    	//Titre et class active sur le menu
        $this->tag->prependTitle('Tableau de bord - ');
        $this->view->setVar('activeClass', 'dashboard');
    }

}

