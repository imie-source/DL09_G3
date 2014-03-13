<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Nannyster\Auth\Auth;
use Nannyster\Models\Mailboxes;
use Nannyster\Forms\LoginForm;
use Nannyster\Forms\ForgotPasswordForm;

class ControllerBase extends Controller
{
    /**
     * Execute before the router so we can determine if this is a provate controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();

        // Only check permissions on private controllers
        /*if ($this->acl->isPrivate($controllerName)) {*/

            // Get the current identity
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                //$profile = Profiles::find(array(array('name' => 'Guest')));
                $identity['profile_name'] = 'Utilisateur';
/*
                $this->flash->error('Vous devez être connecté pour accéder à cette partie du site!');

                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'index'
                ));
                return false;*/
            //}
            }
            // Check if the user have permission to the current option
            $actionName = $dispatcher->getActionName();

            /*var_dump($controllerName).'<br/>';
            var_dump($actionName).'<br/>';
            var_dump($identity).'<br/>';
            die();*/
            if (!$this->acl->isAllowed($identity['profile_name'], $controllerName, $actionName)) {

                if($this->session->has('auth-identity')){
                    $this->flash->error('Vous n\'avez pas accès à cette partie du site');
                    $dispatcher->forward(array(
                        'controller' => 'dashboard',
                        'action' => 'index'
                    ));
                }
                else{
                    $this->flash->error('Vous n\'avez pas accès à cette partie du site');
                    $dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }

                return false;
            }
    }

    public function initialize(){

        //Check if user is logged in
        if($this->session->has('auth-identity')){
            $identity = $this->auth->getIdentity();
            $this->view->identity = $identity;
            $this->view->setTemplateBefore('private');
        }
        else{
            $form = new LoginForm();
            $this->view->formLogin = $form;
            $this->view->setTemplateBefore('public');
        }
    	//Last part of the title
        $this->tag->setTitle($this->config->application->siteTitle);

        //Basic collection of CSS files
        $this->assets
        	->collection('basicCss')
        		->addCss('css/bootstrap.min.css', true)
                ->addCss('css/jquery.gritter.css', true)
        		->addCss('css/font-awesome.min.css', true)
        		/*->addCss('//fonts.googleapis.com/css?family=Ubuntu', false)*/;

		//Theme collection of CSS files
        $this->assets
        	->collection('themeCss')
        		->addCss('css/ace-skins.min.css', true)
                ->addCss('css/ace.min.css', true)
                ->addCss('css/ace-customized.css', true);

		//jQuery
		$this->assets
        	->collection('jQuery')
        		->addJs('js/jquery-2.0.3.min.js');

		//jQuery for IE
		$this->assets
        	->collection('jQueryIE')
        		->addJs('js/jquery-1.10.2.min.js');

		//Basic collection of js files
		$this->assets
        	->collection('basicJs')
        		->addJs('js/bootstrap.min.js')
        		->addJs('js/jquery.gritter.min.js')
                ->addJs('js/common.js')
                ->addJs('js/jquery.timeago.js');

		//Theme collection of js files
		$this->assets
        	->collection('themeJs')
        		->addJs('js/theme-elements.min.js')
        		->addJs('js/theme.min.js')
                ->addJs('js/theme-extra.min.js')
                ->addJs('js/custom.js');	

    }

    public static function validateMongoId($id){
        if(preg_match('/^[0-9a-z]{24}$/', $id)){
            return true;
        }
        else{
            return false;
        }
    }

    public static function returnDateTime($date = null, $what = null) {
        if($date != null){
            if($what == 'date'){
                return date('d-m-Y', $date);
            }
            elseif($what == 'time'){
                return date('H:i:s', $date);
            }
            else{
                return date('d-m-Y à H:i:s', $date);
            }
        }
        else{
            return null;
        }
    }

    public static function uuidV4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
