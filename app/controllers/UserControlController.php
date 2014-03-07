<?php
namespace Nannyster\Controllers;

use Nannyster\Models\EmailConfirmations;
use Nannyster\Models\ResetPasswords;
use Nannyster\Models\Users;

/**
 * UserControlController
 * Provides help to users to confirm their passwords or reset them
 */
class UserControlController extends ControllerBase
{

    public function initialize()
    {
        if ($this->session->has('auth-identity')) {
            $this->view->setTemplateBefore('private');
        }
    }

    /**
     * Confirms an e-mail, if the user must change thier password then changes it
     */
    public function confirmEmailAction()
    {
        $code = $this->dispatcher->getParam('code');

        $confirmation = EmailConfirmations::findFirst(array(array('code' => $code)));

        if (!$confirmation) {
            return $this->response->redirect('index');
        }

        if ($confirmation->confirmed != 'N') {
            $this->flashSession->warning('Cette adresse email a déjà été validée!');
            return $this->response->redirect('index');
        }

        $user = Users::findById($confirmation->user_id);

        $confirmation->confirmed = 'Y';

        $user->active = 'Y';

        /**
         * Change the confirmation to 'confirmed' and update the user to 'active'
         */
        if (!$confirmation->save() || !$user->save()) {

            foreach ($confirmation->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            foreach ($user->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            return $this->response->redirect('index');
        }

        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($user->_id);

        /**
         * Check if the user must change his/her password
         */
        if ($user->mustChangePassword == 'Y') {

            $this->flashSession->success('Votre email a bien été confirmé. Maintenant vous devez changé votre mot de passe');

            return $this->dispatcher->forward(array(
                'controller' => 'users',
                'action' => 'changePassword'
            ));
        }

        $this->flashSession->success('Votre email a bien été confirmé.');

        return $this->response->redirect('dashboard');
    }

    public function resetPasswordAction()
    {
        $code = $this->dispatcher->getParam('code');

        $resetPassword = ResetPasswords::findFirst(array(array('code' => $code)));
        if (!$resetPassword) {
            $this->flash->error('Ce lien de réinitialisation n\'est pas valide.');
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        if ($resetPassword->reset != 'N') {
            $this->flash->error('Votre mot de passe à déjà été changé avec ce lien de réinitialisation. Si vous avez oublié votre mot de passe, merci de faire une nouvelle demande de réinitialisation.');
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        $resetPassword->reset = 'Y';

        /**
         * Change the confirmation to 'reset'
         */
        if (!$resetPassword->save()) {

            foreach ($resetPassword->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($resetPassword->user_id);

        //$this->flash->success('Vous pouvez maintenant définir votre nouveau mot de passe');

        return $this->response->redirect('users/changePassword');
    }
}
