<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\View;
use Phalcon\Mvc\Collection;
use Nannyster\Models\Users;
use Nannyster\Models\Profiles;
use Nannyster\Models\PasswordChanges;
use Nannyster\Models\ResetPasswords;
use Nannyster\Forms\ChangePasswordForm;
use Nannyster\Models\Promotions;

class UsersController extends ControllerBase
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

  /**
   * Users must use this action to change its password
   */
  public function changePasswordAction()
  {
    $this->tag->prependTitle('Changer mon mot de passe - ');
    $user = $this->auth->getUser();
    $this->view->setVar('breadcrumbs', array(
        'Profil de '.ucwords($user->firstname).' '.(($user->wedding_surname != null) ? strtoupper($user->wedding_surname) : strtoupper($user->surname)) => array(
            'controller' => 'users',
            'action' => 'view'),
        'Modifier mon mot de passe' => array(
            'last' => true)
    ));
    $form = new ChangePasswordForm();

    if ($this->request->isPost()) {

        if (!$form->isValid($this->request->getPost())) {

            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
        } else {

            $user->password = $this->security->hash($this->request->getPost('password'));
            $user->mustChangePassword = 'N';

            $passwordChange = new PasswordChanges();
            $passwordChange->user_id = $user->_id;
            $passwordChange->ip_address = $this->request->getClientAddress();
            $passwordChange->user_agent = $this->request->getUserAgent();

            if (!$user->save()) {
                $this->flash->error($passwordChange->getMessages());
            } else {

                $this->flash->success('Votre mot de passe a bien été modifié');
                $this->dispatcher->forward(array(
                    'controller' => 'users',
                    'action' => 'view'
                ));
            }
        }
      }

      $this->view->form = $form;
  }

  public function viewAction($id){

    $this->assets->addJs('js/jquery.slimscroll.min.js');
    $this->assets->addJs('js/jquery-ui-1.10.3.custom.min.js');
    $this->assets->addJs('js/jquery.ui.touch-punch.min.js');
    $this->assets->addJs('js/bootbox.min.js');
    $this->assets->addJs('js/jquery.hotkeys.min.js');
    $this->assets->addJs('js/bootstrap-wysiwyg.min.js');
    $this->assets->addJs('js/select2.min.js');
    $this->assets->addJs('js/date-time/bootstrap-datepicker.min.js');
    $this->assets->addJs('js/fuelux/fuelux.spinner.min.js');
    $this->assets->addJs('js/jquery.maskedinput.min.js');
    $this->assets->addJs('js/jquery.easy-pie-chart.min.js');
    $this->assets->addJs('js/jquery.cookie.js');

    if($id != null){

      if(self::validateMongoId($id)){
        //Retrieve user infos
        $user = Users::findById(new \MongoId($id));

        //If viewed user is the logged user, update is allowed
        if($id == $this->auth->getId()){
          $updateAllowed = true;
        }
        //If not, update is disabled
        else{
          $updateAllowed = false;
        }
      }
      else{
        $this->flash->error('L\'utilisateur demandé n\'existe pas!');
        $this->response->redirect('users/search');
      }
    }
    elseif($this->session->has('auth-identity')){
        //Retrieve user infos
        $user = Users::findById(new \MongoId($this->auth->getId()));
        $promotion = Promotions::findbyId(new \MongoId($user->promotion_id));
        //Enabled update
        $updateAllowed = true;
    }
    //If $id is not defined, we show current logged user
    else{
      $this->flash->error('L\'utilisateur demandé n\'existe pas!');
      $this->response->redirect('users/search');
    }

    //If user exists
    if($user != false){
      $profile = Profiles::findById(new \MongoId($user->profile_id));
    }

    //Or not, redirecting to dashboard page with an error message
    else{
      $this->flash->error('L\'utilisateur demandé n\'existe pas!');
      $this->response->redirect('users/search');
    }

    //Define usefull js and css if update is allowed
    if($updateAllowed){
      $this->assets->addJs('js/bootstrap-editable.js');
      $this->assets->addCss('css/bootstrap-editable.css');
      $this->assets->addJs('js/x-editable/ace-editable.min.js');
      $this->assets->addJs('js/plupload/plupload.js');
      $this->assets->addJs('js/plupload/plupload.flash.js');
      $this->assets->addJs('js/plupload/plupload.html5.js');
      $this->assets->addJs('js/users/view.js');
    }

    //Define page title and breadcrumbs
    $this->tag->prependTitle('Profil de '.ucwords($user->name).' '.strtoupper($user->surname).' - ');
    $this->view->setVar('breadcrumbs', array('Profil de '.ucwords($user->name).' '.strtoupper($user->surname) => array('last' => true)));

    //Pass vars to view
    $this->view->setVar('updateAllowed', $updateAllowed);
    $this->view->setVar('profile', $profile);
    $this->view->setVar('user', $user);
    $this->view->setVar('promotion', $promotion);
  }

  public function editAction($id){

    $this->assets->addJs('js/jquery.slimscroll.min.js');
    $this->assets->addJs('js/jquery-ui-1.10.3.custom.min.js');
    $this->assets->addJs('js/jquery.ui.touch-punch.min.js');
    $this->assets->addJs('js/bootbox.min.js');
    $this->assets->addJs('js/jquery.hotkeys.min.js');
    $this->assets->addJs('js/bootstrap-wysiwyg.min.js');
    $this->assets->addJs('js/select2.min.js');
    $this->assets->addJs('js/date-time/bootstrap-datepicker.min.js');
    $this->assets->addJs('js/fuelux/fuelux.spinner.min.js');
    $this->assets->addJs('js/jquery.maskedinput.min.js');
    $this->assets->addJs('js/jquery.easy-pie-chart.min.js');
    $this->assets->addJs('js/jquery.cookie.js');
    $this->assets->addJs('js/bootstrap-editable.js');
    $this->assets->addCss('css/bootstrap-editable.css');
    $this->assets->addJs('js/x-editable/ace-editable.min.js');
    $this->assets->addJs('js/plupload/plupload.js');
    $this->assets->addJs('js/plupload/plupload.flash.js');
    $this->assets->addJs('js/plupload/plupload.html5.js');
    //$this->assets->addJs('css/jquery.fileupload.js');
    //$this->assets->addJs('js/users/view.js');
    $this->assets->addJs('js/users/edit.js');

    if($id != null){

      if(self::validateMongoId($id)){
        //Retrieve user infos
        $user = Users::findById(new \MongoId($id));

        //Update is allowed
          $updateAllowed = true;
      }
      else{
        $this->flash->error('L\'utilisateur demandé n\'existe pas!');
        $this->response->redirect('users/manage');
      }
    }

    //If $id is not defined, we show current logged user
    else{
      $this->flash->error('L\'utilisateur demandé n\'existe pas!');
      $this->response->redirect('users/manage');
    }

    //If user exists
    if($user != false){
      $profiles = Profiles::find();
      $profile = Profiles::findById(new \MongoId($user->profile_id));
      $promotion = Promotions::findbyId(new \MongoId($user->promotion_id));
    }

    //Or not, redirecting to dashboard page with an error message
    else{
      $this->flash->error('L\'utilisateur demandé n\'existe pas!');
      $this->response->redirect('users/manage');
    }

    //Define page title and breadcrumbs
    $this->tag->prependTitle('Profil de '.ucwords($user->firstname).' '.(($user->wedding_surname != null) ? strtoupper($user->wedding_surname) : strtoupper($user->surname)).' - ');
    $this->view->setVar('breadcrumbs', array(
      'Administration' => array(
        'controller' => 'administration',
        'action' => 'index'),
      'Manager d\'utilisateurs' => array(
        'controller' => 'users',
        'action' => 'manage'),
      'Profil de '.ucwords($user->firstname).' '.(($user->wedding_surname != null) ? strtoupper($user->wedding_surname) : strtoupper($user->surname)) => array(
        'last' => true)));

    //Pass vars to view
    $this->view->setVar('updateAllowed', $updateAllowed);
    $this->view->setVar('profiles', $profiles);
    $this->view->setVar('profile', $profile);
    $this->view->setVar('promotion', $promotion);
    $this->view->setVar('user', $user);
  }

	public function ajaxUsersForNewMailAction() {
		if($this->request->isPost()){
			if($this->request->isAjax()){

        //response view without layout
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        //Define User MongoId to avoid returning his name in users result list
        $arrayMongoId[] = $this->auth->getId();

        $data = $this->request->getPost();
        if(array_key_exists('e', $this->request->getPost()) > 0){
          foreach ($data['e']['id'] as $key) {
            $arrayMongoId[] = new \MongoId($key);
          }
          $users = Users::find(array(array('fullname' => array('$regex' => $data['q']),
                                           '_id' => array('$nin' => $arrayMongoId),
                                           'active' => 'Y')));
        }
        else{
				  $users = Users::find(array(array('fullname' => array('$regex' => $data['q']),
                                           '_id' => array('$nin' => $arrayMongoId),
                                           'active' => 'Y')));
        }
        if(count($users) > 0){
          foreach ($users as $key) {
            echo '<a href="#" id="user-result-span" class="user-result-span" data-id="'.$key->_id.'" data-name="'.ucfirst($key->firstname).' '.(($key->wedding_surname != null) ? strtoupper($key->wedding_surname) : strtoupper($key->surname)).'">'.ucfirst($key->firstname).' '.(($key->wedding_surname != null) ? strtoupper($key->wedding_surname) : strtoupper($key->surname)).' <span class="user-result-ville">('.$key->city.')</span></a>';
          }
        }
        else{
            echo '<span class="user-no-result-span">Aucun résultat</span>';
        }
        die();
			}
		}
		$this->response->redirect('dashboard');
    }

    public function ajaxProfileEditorAction() {
      if($this->request->isPost()){
        if ($this->request->isAjax()) {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            $data = $this->request->getPost();
            if ($data['pk'] == $this->auth->getId()){
                $user = Users::findById(new \MongoId($data['pk']));

                //Field array to exclude from strtolower before saving user
                $arrayField = array('email_notification', 'private_informations', 'directory_visible', 'directory_private_informations', 'banned', 'suspended', 'active', 'mustChangePassword');
                if(in_array($data['name'], $arrayField)){
                  $user->$data['name'] = $data['value'];
                }
                else{
                  $user->$data['name'] = strtolower($data['value']);
                }
                if ($user->save()) {
                    $_SESSION['auth-identity'][$data['name']] = $data['value'];
                    return $this->response->setStatusCode(200, 'OK');
                } else {
                    return $this->response->setStatusCode(400, 'NOK');
                }
            } else {
                return $this->response->setStatusCode(400, 'NOK');
            }
        }
      }
      $this->response->redirect('dashboard');
    }

    public function ajaxAdminProfileEditorAction() {
      if($this->request->isPost()){
        if ($this->request->isAjax()) {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            $data = $this->request->getPost();
            $user = Users::findById(new \MongoId($data['pk']));

            //Field array to exclude from strtolower before saving user
            $arrayField = array('email_notification', 'private_informations', 'directory_visible', 'directory_private_informations', 'banned', 'suspended', 'active', 'mustChangePassword');
            if(in_array($data['name'], $arrayField)){
              $user->$data['name'] = $data['value'];
            }
            else{
              $user->$data['name'] = strtolower($data['value']);
            }
            if ($user->save()) {
                return $this->response->setStatusCode(200, 'OK');
            } else {
                return $this->response->setStatusCode(400, 'NOK');
            }
        }
      }
      $this->response->redirect('dashboard');
    }

    public function ajaxImageEditorAction(){
      if($this->request->hasFiles()){
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        //retrieve user identity
        $user = $this->auth->getIdentity();

        //define upload folder
        $uploadFolder = "/public/img/avatars";

        //define full path to upload folder
        $uploadPath = BASE_DIR . $uploadFolder;

        // Proceed each images
        foreach ($this->request->getUploadedFiles() as $file) {

          //Check file type
          switch ($file->getType()) {
            case 'image/png':
              $ext = 'png';
              break;
            
            case 'image/jpeg':
              $ext = 'jpeg';
              break;
            
            case 'image/gif':
              $ext = 'gif';
              break;
            
            default:
              die('{"error":true, "message": "Votre photo n\'est pas au bon format. Vous ne pouvez télécharger que des photos aux formats .jpeg, .png ou .gif. Veuillez recommencer", "oldImage": "' . $user['avatar'] . '"}');
              break;
          }

          if($file->getError() == 0){

            //Define new image name
            $newImageName = self::uuidV4().'.'.$ext;

            //check if file exists in upload folder
            while(file_exists($uploadPath . '/' . $newImageName)) {
              //Define a newer image name
              $newImageName = self::uuidV4().'.'.$ext;
            }

            //create full path with image name
            $full_image_path = $uploadPath . '/' . $newImageName;

            //Move the picture to img/avatar folder
            if ($file->moveTo($full_image_path)) {
                $this->resize($full_image_path, $ext, $uploadPath.'/thumbs/' . $newImageName, 48, 48);

                //If the user has already upload a custom profile picture, we delete it and its avatar
                if (file_exists('img/avatars/' . $user['avatar']) && $user['avatar'] != 'default.jpg') {
                    unlink('img/avatars/' . $user['avatar']);
                }
                if (file_exists('img/avatars/thumbs/' . $user['avatar']) && $user['avatar'] != 'default.jpg') {
                    unlink('img/avatars/thumbs/' . $user['avatar']);
                }

                //Updating avatar's value in session
                $_SESSION['auth-identity']['avatar'] = $newImageName;

                //And save it in mongo
                $user = Users::findById(new \MongoId($user['_id']));
                $user->avatar = $newImageName;
                $user->save();

                die('{"error":false, "message": "Votre photo a bien été téléchargée", "newImage": "' . $newImageName . '"}');
            }
            else {
                die('{"error":true, "message": "Il y a eu une erreur lors du téléchargement de votre photo. Veuillez recommencer", "oldImage": "' . $user['avatar'] . '"}');
            }
          }
          else {
              die('{"error":true, "message": "Il y a eu une erreur lors du téléchargement de votre photo. Veuillez recommencer", "oldImage": "' . $user['avatar'] . '"}');
          }
        }
      }
    }
/*
    public function ajaxImageEditorOldAction() {


        if ($this->request->isPost()) {
          if($this->request->isAjax()){
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            //retrieve user identity
            $user = $this->auth->getIdentity();
            if ($_FILES['file']) {
                $image = $_FILES['file'];
                //upload folder - make sure to create one in webroot
                $uploadFolder = "img/avatars";
                //full path to upload folder
                $uploadPath = BASE_DIR . $uploadFolder;


                if ($image['type'] == "image/png" || $image['type'] == "image/jpeg" || $image['type'] == "image/gif") {
                    if ($image['type'] == "image/png") {
                        $ext = 'png';
                    } else if ($image['type'] == "image/jpeg") {
                        $ext = 'jpeg';
                    } else if ($image['type'] == "image/gif") {
                        $ext = 'gif';
                    }
                    //check if there wasn't errors uploading file on serwer
                    if ($image['error'] == 0) {
                        //image file name
                        $imageName = self::uuidV4() . '.' . $ext;
                        //check if file exists in upload folder
                        if (file_exists($uploadPath . '/' . $imageName)) {
                            //create full filename with timestamp
                            $imageName = date('His') . $imageName;
                        }
                        //create full path with image name
                        $full_image_path = $uploadPath . '/' . $imageName;

                        //upload image to upload folder
                        if (move_uploaded_file($image['tmp_name'], $full_image_path)) {
                            $this->resize('img/avatars/' . $imageName, $ext, 'img/avatars/thumbs/' . $imageName, 48, 48);
                            if (file_exists('img/avatars/' . $user['avatar']) && $user['avatar'] != 'default.jpg') {
                                unlink('img/avatars/' . $user['avatar']);
                            }
                            if (file_exists('img/avatars/thumbs/' . $user['avatar']) && $user['avatar'] != 'default.jpg') {
                                unlink('img/avatars/thumbs/' . $user['avatar']);
                            }
                            $_SESSION['auth-identity']['avatar'] = $imageName;
                            $user = Users::findById(new \MongoId($user['id']));
                            $user->avatar = $imageName;
                            $user->save();
                            return json_encode(array(
                              'error' => false,
                              'message' => 'Votre photo a bien été téléchargée',
                              'newImage' => $imageName
                            ));
                        } else {
                            return json_encode(array(
                              'error' => true,
                              'message' => 'Il y a eu une erreur lors du téléchargement de votre photo. Veuillez recommencer',
                              'oldImage' => $user['avatar']
                            ));
                        }
                    } else {
                        return json_encode(array(
                          'error' => true,
                          'message' => 'Il y a eu une erreur lors du téléchargement de votre photo. Veuillez recommencer',
                          'oldImage' => $user['avatar']
                        ));
                    }
                } else {
                    return json_encode(array(
                      'error' => true,
                      'message' => 'Votre photo comporte des incohérences dans sa structure. Par sécurité nous ne pouvons pas l\'enregistrer. Si le problème persiste, veuillez nous contacter.',
                      'oldImage' => $user['avatar']
                    ));
                }
            }
          }
        }
        $this->response->redirect('dashboard');
    }*/

    private function resize($img, $ext, $dest, $largeur = 0, $hauteur = 0) {

        $useGD = true; // On utilise la librairie GD ?
        $quality = 90;

        // On récupère les dimensions de l'image
        $dimension = getimagesize($img);
        $ratio = 1; // Et son ratio
        if ($dimension[0] < $dimension[1]) {
            $largeur = $hauteur;
            $hauteur = 0;
        }
        if ($dimension[0] >= $dimension[1]) {
            $hauteur = 0;
        }

        // On trouve les dimension finale 
        // (si on a passé 0 en paramètre c'est que l'on veut que le paramètre s'adapte pour conserver le ratio)
        if ($largeur == 0 && $hauteur == 0) {
            $largeur = $dimension[0];
            $hauteur = $dimension[1];
        } else if ($hauteur == 0) {
            $hauteur = round($largeur / $ratio);
        } else if ($largeur == 0) {
            $largeur = round($hauteur * $ratio);
        }

        // Si on doit "cropper" l'image on cherche de cb de px on doit décaler l'image miniatures pour la centrer
        if ($dimension[0] > ($largeur / $hauteur) * $dimension[1]) {
            $dimY = $hauteur;
            $dimX = round($hauteur * $dimension[0] / $dimension[1]);
            $decalX = ($dimX - $largeur) / 2;
            $decalY = 0;
        }
        if ($dimension[0] < ($largeur / $hauteur) * $dimension[1]) {
            $dimX = $largeur;
            $dimY = round($largeur * $dimension[1] / $dimension[0]);
            $decalY = ($dimY - $hauteur) / 2;
            $decalX = 0;
        }
        if ($dimension[0] == ($largeur / $hauteur) * $dimension[1]) {
            $dimX = $largeur;
            $dimY = $hauteur;
            $decalX = 0;
            $decalY = 0;
        }

        // On crée l'image avec la librairie GD
        if ($useGD) {
            $miniature = imagecreatetruecolor($largeur, $hauteur);
            if ($ext == 'jpeg' || $ext == 'jpg') {
                $image = imagecreatefromjpeg($img);
            }
            if ($ext == 'png') {
                $image = imagecreatefrompng($img);
            }
            if ($ext == 'gif') {
                $image = imagecreatefromgif($img);
            }

            imagecopyresampled($miniature, $image, -$decalX, -$decalY, 0, 0, $dimX, $dimY, $dimension[0], $dimension[1]);
            imagejpeg($miniature, $dest, $quality);

            return true;

            // Ou on utilise imagemagick
        } else {
            $cmd = '/usr/bin/convert -resize ' . $dimX . 'x' . $dimY . ' "' . $img . '" "' . $dest . '"';
            shell_exec($cmd);

            $cmd = '/usr/bin/convert -gravity Center -quality ' . $quality . ' -crop ' . $largeur . 'x' . $hauteur . '+0+0 -page ' . $largeur . 'x' . $hauteur . ' "' . $dest . '" "' . $dest . '"';
            shell_exec($cmd);
        }
        return true;
    }

    /**
     * Retrieve each mother account for a new contract
     */
    public function ajaxMotherUsersForNewContractAction() {
      if($this->request->isPost()){
        if($this->request->isAjax()){
          $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
          $data = $this->request->getPost();

          $users = Users::find(array(array(
            'civility' => 'Mme',
            'fullname' => array('$regex' => $data['q']),
            '_id' => array('$nin' => array(new \MongoId($this->auth->getId()))),
            'active' => 'Y'
          )));

          if ($users) {
                foreach ($users as $user) {
                    echo '<a href="#" id="user-result-span" class="user-result-span" data-id="' . $user->_id . '" data-firstname="'.ucwords($user->firstname).'" data-surname="'.strtoupper($user->surname).'" data-address="'.ucwords($user->address).'" data-zip-code="'.$user->zipcode.'" data-city="'.ucwords($user->city).'" data-phone="'.$user->phone.'" data-mobile="'.$user->mobile.'" data-email="'.$user->email.'" data-pajemploi="'.strtoupper($user->nopajemploi).'">' . ucwords($user->firstname).' '.strtoupper($user->surname).' <span class="user-result-ville">(' . ucwords($user->city) . ')</span></a>';
                }
                return true;
            }
            else{
                echo '<span class="user-no-result-span">Aucun résultat</span>';
                return true;
            }
        }
      }
      $this->response->redirect('contracts');
    }

    /**
     * Retrieve each father account for a new contract
     */
    public function ajaxFatherUsersForNewContractAction() {
      if($this->request->isPost()){
        if($this->request->isAjax()){
          $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
          $data = $this->request->getPost();

          $users = Users::find(array(array(
            'civility' => 'Mr',
            'fullname' => array('$regex' => $data['q']),
            '_id' => array('$nin' => array(new \MongoId($this->auth->getId()))),
            'active' => 'Y'
          )));

          if ($users) {
                foreach ($users as $user) {
                    echo '<a href="#" id="user-result-span" class="user-result-span" data-id="' . $user->_id . '" data-firstname="'.ucwords($user->firstname).'" data-surname="'.strtoupper($user->surname).'" data-address="'.ucwords($user->address).'" data-zip-code="'.$user->zipcode.'" data-city="'.ucwords($user->city).'" data-phone="'.$user->phone.'" data-mobile="'.$user->mobile.'" data-email="'.$user->email.'" data-pajemploi="'.strtoupper($user->nopajemploi).'">' . ucwords($user->firstname).' '.strtoupper($user->surname).' <span class="user-result-ville">(' . ucwords($user->city) . ')</span></a>';
                }
                return true;
            }
            else{
                echo '<span class="user-no-result-span">Aucun résultat</span>';
                return true;
            }
        }
      }
      $this->response->redirect('contracts');
    }

    public function deleteAction($id){
      if($id != null){
        if(self::validateMongoId($id)){
          $user = Users::findById(new \MongoId($id));

          if($user){
            if($user->delete()){
              $this->flash->success('L\'utilisateur a bien été supprimé');
              return $this->response->redirect('users/manage');
            }
          }
        }
      }
      $this->flash->error('L\'utilisateur n\'existe pas!');
      $this->response->redirect('users/manage');
    }
}
