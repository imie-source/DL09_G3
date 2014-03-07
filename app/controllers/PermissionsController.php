<?php
namespace Nannyster\Controllers;

use Nannyster\Models\Profiles;
use Nannyster\Models\Permissions;

/**
 * View and define permissions for the various profile levels.
 */
class PermissionsController extends ControllerBase
{

    /**
     * View the permissions for a profile level, and change them if we have a POST.
     */
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

            // Validate the profile
            $profile = Profiles::findById(new \MongoId($this->request->getPost('profile_id')));
   
            if ($profile) {

                if ($this->request->hasPost('permissions')) {


                    // Deletes the current permissions
                    $permissions = Permissions::find(array(array(
                        'profile_id' => $profile->_id
                    )));
                    foreach ($permissions as $permission) {
                        $permission->delete();
                    }

                    // Save the new permissions
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

                // Rebuild the ACL with
                $this->acl->rebuild();

                // Pass the current permissions to the view
                $this->view->permissions = $this->acl->getPermissions($profile);
            }

            $this->view->profile = $profile;
        }

        // Pass all the active profiles
        $this->view->profiles = Profiles::find(array(array(
            'active' => 'Y')));
    }
}
