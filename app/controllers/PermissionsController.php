<?php
namespace Nannyster\Controllers;

use Nannyster\Models\Profiles;
use Nannyster\Models\Permissions;

class PermissionsController extends ControllerBase
{

    public function indexAction()
    {
        $this->tag->prependTitle('Permissions - ');
        $this->view->setVar('activeClass', 'administration');
        $this->view->setVar('breadcrumbs', array(
            'Administration' => array(
                'controller' => 'administration',
                'action' => 'index'),
            'Permissions' => array(
                'last' => true)
        ));


        if ($this->request->isPost()) {

            // Valide le profil
            $profile = Profiles::findById(new \MongoId($this->request->getPost('profile_id')));
   
            if ($profile) {

                if ($this->request->hasPost('permissions')) {


                    //Efface les permissions actuelles
                    $permissions = Permissions::find(array(array(
                        'profile_id' => $profile->_id
                    )));
                    foreach ($permissions as $permission) {
                        $permission->delete();
                    }

                    // Enregistre les nouvelles permissions
                    foreach ($this->request->getPost('permissions') as $permission) {

                        $parts = explode('.', $permission);

                        $permission = new Permissions();
                        $permission->profile_id = $profile->_id;
                        $permission->resource = $parts[0];
                        $permission->action = $parts[1];

                        $permission->save();
                    }

                    $this->flash->success('Les permissions ont bien été mises à jour!');
                }

                // Reconstruit le fichier ACL
                $this->acl->rebuild();

                //Passe les permissions courantes à la vue
                $this->view->permissions = $this->acl->getPermissions($profile);
            }

            $this->view->profile = $profile;
        }

        //Passe tous les profiles actifs à la vue
        $this->view->profiles = Profiles::find(array(array(
            'active' => 'Y')));
    }
}
