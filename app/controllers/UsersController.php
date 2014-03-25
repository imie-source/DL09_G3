<?php

namespace Nannyster\Controllers;

use Phalcon\Mvc\View;
use Phalcon\Mvc\Collection;
use Nannyster\Models\Users;
use Nannyster\Models\UsersSkills;
use Nannyster\Models\Skills;
use Nannyster\Models\Profiles;
use Nannyster\Models\PasswordChanges;
use Nannyster\Models\ResetPasswords;
use Nannyster\Forms\ChangePasswordForm;
use Nannyster\Models\Promotions;
use Nannyster\Models\Schools;
use Nannyster\Utils\Slug;

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
    $this->view->setVar('schools', Schools::find());
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

  public function viewAction($id = null){

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
        //Enable update
        $updateAllowed = true;
    }
    //If $id is not defined, we show current logged user
    else{
      $this->flash->error('L\'utilisateur demandé n\'existe pas!');
      $this->response->redirect('users/search');
    }

    //If user exists
    if($user != false){
      $promotion = Promotions::findbyId(new \MongoId($user->promotion_id));
        $skills = UsersSkills::find(array(array(
          'user_id' => (string) $user->_id
        )));
        if($skills){
          for ($i = 0; $i < sizeof($skills); $i++) { 
            $skills[$i]->name = Skills::findById(new \MongoId($skills[$i]->skill_id));
          }
        }
      $profile = Profiles::findById(new \MongoId($user->profile_id));
      $school = Schools::findById(new \MongoId($user->school_id));
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
    $this->view->setVar('school', $school);
    $this->view->setVar('skills', $skills);
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
      $skills = UsersSkills::find(array(array(
          'user_id' => (string) $user->_id
        )));
        if($skills){
          for ($i = 0; $i < sizeof($skills); $i++) { 
            $skills[$i]->name = Skills::findById(new \MongoId($skills[$i]->skill_id));
          }
        }
      $profiles = Profiles::find();
      $profile = Profiles::findById(new \MongoId($user->profile_id));
      $promotion = Promotions::findbyId(new \MongoId($user->promotion_id));
      $school = Schools::findById(new \MongoId($user->school_id));
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
    $this->view->setVar('skills', $skills);
    $this->view->setVar('promotion', $promotion);
    $this->view->setVar('school', $school);
    $this->view->setVar('user', $user);
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

 public function indexAction(){
    $this->tag->prependTitle('Manager d\'utilisateurs - ');
    $user = $this->auth->getUser();
    $this->view->setVar('breadcrumbs', array(
      'Liste des utilisateurs' => array(
                'last' => true)
        ));
    $this->assets->addJs('js/jquery.dataTables.min.js');
    $this->assets->addJs('js/jquery.dataTables.bootstrap.js');
    $this->assets->addJs('js/bootbox.min.js');
    $this->assets->addJs('js/users/index.js');

    $users = Users::find();
    for ($i = 0; $i < sizeof($users); $i++) { 
      $usersSkills[$i] = UsersSkills::find(array(array(
        'user_id' => (string) $users[$i]->_id
      )));
      if($usersSkills[$i]){
        for ($j = 0; $j < sizeof($usersSkills[$i]); $j++) { 
          $skillName[$i][$j] = Skills::findById(new \MongoId($usersSkills[$i][$j]->skill_id));
        }
      }
    }
    




    $this->view->setVar('users', $users);
    $this->view->setVar('usersSkills', $usersSkills);
    $this->view->setVar('skillName', $skillName);
    $this->view->setVar('profiles', Profiles::find());
    $this->view->setVar('promotions', Promotions::find());
    $this->view->setVar('schools', Schools::find());
 }

 public function skillsAction(){

    $this->tag->prependTitle('Mes compétences - ');
    $this->view->setVar('activeClass', 'skills');
    $this->view->setVar('breadcrumbs', array(
        'Mes compétences' => array(
            'last' => true)
    ));
    $this->assets->addJs('js/fuelux/fuelux.tree.min.js');
    $this->assets->addJs('js/jquery.raty.js');
    $this->assets->addJs('js/users/skills.js');

    if($this->request->isPost()){
      $data = $this->request->getPost();

      foreach ($data['skills'] as $skill) {
        $usersSkill = new UsersSkills();
        $usersSkill->user_id = (string) $this->auth->getId();
        $usersSkill->skill_id = $skill['id'];
        $usersSkill->rate = $skill['rate'];

        $usersSkill->save();
      }
      $this->flashSession->success('Vos compétences ont bien été mises à jour!');
      $this->response->redirect('users/view');
    }
  }

  public function skillDeleteAction($id){
    if(!empty($id)){
      if(self::validateMongoId($id)){
        $skill = UsersSkills::findById(new \MongoId($id));

        if($skill){
          if($skill->delete()){
            $this->flashSession->success('La compétence a bien été supprimée');
            $this->response->redirect('users/view');
          }
          else{
            $this->flashSession->error('La compétence n\'a pu être supprimée');
            $this->response->redirect('users/view');
          }
        }
        else{
          $this->flashSession->error('Cette compétence ne vous apartient pas!!!');
          $this->response->redirect('users/view');
        }
      }
      else{
        $this->flashSession->error('L\'identifiant de cette compétence n\'est pas valide!!!');
        $this->response->redirect('users/view');
      }
    }
    $this->response->redirect('users/view');
  }

  public function importAction(){

    $this->assets->addJs('js/users/import.js');

    if($this->request->isPost()){
      $datas = $this->request->getPost();

      if($_FILES["file"]["type"] != "text/csv"){
        $this->flash->error('Le fichier envoyer n\'est pas au format csv');
        return $this->response->redirect('users/import');
      }
      elseif(is_uploaded_file($_FILES['file']['tmp_name'])){
        
        $handle = fopen($_FILES['file']['tmp_name'], "r");
        $data = fgetcsv($handle, 1000, ","); //Remove if CSV file does not have column headings
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          if(!empty($data[5]) && !empty($data[7])){
            $user = new Users();
            $user->_id = null;
            $user->surname = $data[5];
            $user->name = $data[7];
            $user->date_birth = $data[20];
            $user->address = $data[8].($data[9] != '' ? ', '.$data[9] : '');
            $user->zipcode = $data[10];
            $user->city = $data[11];
            $user->email = $data[14];
            $user->mobile = $data[12];

            $profile = Profiles::find(array(array(
              'name' => 'Utilisateur')
            ));
            $user->profile_id = $profile[0]->_id;

            $promotion = Promotions::find(array(array(
              'name' => $data[3])
            ));
            if(!$promotion){
              $promotion = new Promotions();
              $promotion->name = $data[3];
              $promotion->save();
              $user->promotion_id = (string) $promotion->_id;
              $promoSlug = $promotion->name;
            }
            else{
              $user->promotion_id = (string) $promotion[0]->_id;
              $promoSlug = $promotion[0]->name;
            }
            

            $school = Schools::find(array(array(
              'name' => strtolower($data[1]))
            ));
            if(!$school){
              $school = new Schools();
              $school->name = strtolower($data[1]);
              $school->save();
              $user->school_id = (string) $school->_id;
            }
            else{
              $user->school_id = (string) $school[0]->_id;
            }


            $user->login = \Nannyster\Utils\Slug::generate($user->name).'.'.\Nannyster\Utils\Slug::generate($user->surname).'.'.\Nannyster\Utils\Slug::generate($promoSlug);
            $user->password = $this->security->hash('P@ssword');
            $user->save();
          }
        }
        $this->flash->success('Le ficiher a été importé avec succès!');
      }
    }

  }

}
