<?php

namespace Nannyster\Controllers;

class DashboardController extends ControllerBase
{

    public function indexAction()
    {
        $this->tag->prependTitle('Tableau de bord - ');
        $this->view->setVar('activeClass', 'dashboard');
    }

}

