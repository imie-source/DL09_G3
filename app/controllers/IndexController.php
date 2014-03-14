<?php

namespace Nannyster\Controllers;

use Nannyster\Forms\LoginForm;
use Nannyster\Forms\ForgotPasswordForm;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->tag->prependTitle('Home - ');
        $this->view->setVar('activeClass', 'home');

            $form = new LoginForm();
            $this->view->formLogin = $form;
            $this->view->setTemplateBefore('launch');


    }

}

