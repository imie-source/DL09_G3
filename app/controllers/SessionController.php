<?php

namespace Nannyster\Controllers;

use Nannyster\Forms\LoginForm;
use Nannyster\Forms\SignUpForm;
use Nannyster\Forms\ForgotPasswordForm;
use Nannyster\Auth\Exception as AuthException;
use Nannyster\Models\Users;
use Nannyster\Models\ResetPasswords;

class SessionController extends ControllerBase{

	/**
	 * Login a user
	 */
	public function loginAction(){
		$form = new LoginForm();
		try{
			// If request dosn't comes from login form
			if(!$this->request->isPost()){
				//Check if the user want be remebered
				if($this->auth->hasRemeberMe()){
					return $this->auth->loginWithRememberMe();
				}
				else{
					$this->response->redirect('index');
				}
			}
			// Comes from login form
			else{
				//Request is not valid
				if(!$form->isValid($this->request->getPost())){
					$error = '<ul>';
	            	foreach ($form->getMessages() as $message) {
							$error.= '<li>'.$message.'</li>';
						}
					$error .= '</ul>';
					$this->flash->error($error);
					return $this->response->redirect('index');
				}
				// Request is valid
				else{

					$this->auth->check(array(
						'login'    => $this->request->getPost('login'),
						'password' => $this->request->getPost('password'),
						'remember' => $this->request->getPost('remember')
					));
					$identity = $this->auth->getIdentity();
					if($identity['first_connection'] == 'y'){
			            $_SESSION['auth-identity']['first_connection'] = 'n';
			            $userConnect = Users::findById(new \MongoId($identity['_id']));
			            $userConnect->first_connection = 'n';
			            $userConnect->save();
			            $this->flash->warning('C\'est votre première connexion. Nous vous conseillons de changer votre mot de passe.');
			            return $this->response->redirect('users/changePassword');
			        }
					return $this->response->redirect('dashboard');
				}
			}

		} catch (AuthException $e) {
			$this->flashSession->error($e->getMessage());
			$this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
		}

	}

    

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = Users::findFirst(array(array('login' => $this->request->getPost('login'))));
                if (!$user) {
                    $this->flash->error('Il n\'y a aucun compte associé à cet email');
        			return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
                } else {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->user_id = $user->_id;
                    if ($resetPassword->save()) {
                        $this->flash->success('Les informations pour réinitialiser votre mot de passe ont été envoyée à l\'adresse '.$user->email);
            			return $this->response->redirect('index');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
            return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
        }
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();
        return $this->response->redirect('index');
    }
}