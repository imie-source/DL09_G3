<?php

namespace Nannyster\Controllers;

use Nannyster\Forms\LoginForm;
use Nannyster\Forms\ForgotPasswordForm;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    	//Titre et class active sur le menu
        $this->tag->prependTitle('Home - ');
        $this->view->setVar('activeClass', 'home');

        //On passe le formulaire de login à la vue
        $form = new LoginForm();
        $this->view->formLogin = $form;

        //On définit le template spécifique à cette action.
        $this->view->setTemplateBefore('launch');


    }

}

