<?php

namespace Nannyster\Controllers;

use Nannyster\Models\Users;
use Nannyster\Forms\LoginForm;
use Nannyster\Forms\SignUpForm;
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

    public function testAction(){
    	$this->view->setVar('dir', var_dump($this->view->setLayout('default.phtml')));
    }

}

