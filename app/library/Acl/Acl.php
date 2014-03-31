<?php
namespace Nannyster\Acl;

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;
use Nannyster\Models\Profiles;
use Nannyster\Models\Permissions;

/**
 * Nannyster\Acl\Acl
 */
class Acl extends Component
{

    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $acl;

    /**
     * The filepath of the ACL cache file from APP_DIR
     *
     * @var string
     */
    private $filePath = '/cache/acl/data.txt';

    /**
     * Define the resources that are considered "private". These controller => actions require authentication.
     *
     * @var array
     */
    private $privateResources = array(
        'administration' => array(
            'index'
        ),
        'dashboard' => array(
            'index'
        ),
        'index' => array(
            'index'
        ),
        'notifications' => array(
            'index'
        ),
        'permissions' => array(
            'index'
        ),
        'projects' => array(
            'index',
            'create',
            'propose',
            'view',
            'valide',
            'delete',
            'addUser',
            'removeUser',
            'masterRemoveUser',
            'addWiki'
        ),
        'session' => array(
            'login',
            'logout'
        ),
        'skills' => array(
            'index',
            'ajaxTreeFinder',
            'add',
            'propose',
            'delete',
            'valide'
        ),
        'team' => array(
            'index'
        ),
        'users' => array(
            'index',
            'edit',
            'view',
            'delete',
            'skills',
            'skillDelete',
            'changePassword',
            'ajaxProfileEditor',
            'ajaxAdminProfileEditor',
            'ajaxImageEditor',
            'import'

        )
    );

    /**
     * Human-readable descriptions of the actions used in {@see $privateResources}
     *
     * @var array
     */
    private $actionDescriptions = array(
        'index'                         => 'Index',
        'search'                        => 'Search',
        'create'                        => 'Create',
        'edit'                          => 'Edit',
        'delete'                        => 'Delete',
        'view'                          => 'View',
        'skillDelete'                   => 'Skill Remover',
        'confirmEmail'                  => 'Email confirmation for new user',
        'resetPassword'                 => 'Reset a forgotten password',
        'forgotPassword'                => 'Forgotten Password',
        'changePassword'                => 'Change password',
        'addUser'                       => 'S\'ajouter à un projet',
        'removeUser'                    => 'Se supprimer d\'un projet',
        'addWiki'                       => 'Ajouter un message au wiki',
        'masterRemoveUser'              => 'Autoriser le chef de projet à supprimer les participants',
        'ajaxCalendarFinder'            => 'Ajax request to retrieve calendar events',
        'ajaxAddEvent'                  => 'Ajax request adding new date in planning',
        'ajaxCreate'                    => 'Ajax request to complete each step of a new contract',
        'ajaxSendNewMail'               => 'Send a message',
        'ajaxMessageView'               => 'View a conversation',
        'ajaxMessageDeleter'            => 'Delete a conversation',
        'ajaxMutipleRemover'            => 'Delete multiple conversations',
        'ajaxMailboxResponseSender'     => 'Reply to a conversation',
        'ajaxProfileEditor'             => 'Ajax request to edit a profile inline',
        'ajaxAdminProfileEditor'        => 'Admin ajax request to edit a profile',
        'ajaxImageEditor'               => 'Ajax request to upload a new user\'s avatar',
        'ajaxMotherUsersForNewContract' => 'Ajax request to retrieve mothers account for a new contract',
        'ajaxFatherUsersForNewContract' => 'Ajax request to retrieve fathers account for a new contract',
        'ajaxSkillsUserUpdater'         => 'Ajax user skills updater'
    );

    /**
     * Checks if a controller is private or not
     *
     * @param string $controllerName
     * @return boolean
     */
    public function isPrivate($controllerName)
    {
        return isset($this->privateResources[$controllerName]);
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $profile
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed($profile, $controller, $action)
    {
        //return true;
        return $this->getAcl()->isAllowed($profile, $controller, $action);
    }

    /**
     * Returns the ACL list
     *
     * @return Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        // Check if the ACL is already created
        if (is_object($this->acl)) {
            return $this->acl;
        }

        // Check if the ACL is in APC
        if (function_exists('apc_fetch')) {
            $acl = apc_fetch('nannyster-acl');
            if (is_object($acl)) {
                $this->acl = $acl;
                return $acl;
            }
        }

        // Check if the ACL is already generated
        if (!file_exists(APP_DIR . $this->filePath)) {
            $this->acl = $this->rebuild();
            return $this->acl;
        }

        // Get the ACL from the data file
        $data = file_get_contents(APP_DIR . $this->filePath);
        $this->acl = unserialize($data);

        // Store the ACL in APC
        if (function_exists('apc_store')) {
            apc_store('nannyster-acl', $this->acl);
        }

        return $this->acl;
    }

    /**
     * Returns the permissions assigned to a profile
     *
     * @param Profiles $profile
     * @return array
     */
    public function getPermissions(Profiles $profile)
    {
        $proflePermissions = Permissions::find(array(array(
            'profile_id' => $profile->_id)));
        $permissions = array();
        foreach ($proflePermissions as $permission) {
            $permissions[$permission->resource . '.' . $permission->action] = true;
        }
        return $permissions;
    }

    /**
     * Returns all the resoruces and their actions available in the application
     *
     * @return array
     */
    public function getResources()
    {
        return $this->privateResources;
    }

    /**
     * Returns the action description according to its simplified name
     *
     * @param string $action
     * @return $action
     */
    public function getActionDescription($action)
    {
        if (isset($this->actionDescriptions[$action])) {
            return $this->actionDescriptions[$action];
        } else {
            return $action;
        }
    }

    /**
     * Rebuilds the access list into a file
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function rebuild()
    {
        $acl = new AclMemory();

        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        // Register roles
        $profiles = Profiles::find(array(array(
            'active' => 'Y')));

        foreach ($profiles as $profile) {
            $acl->addRole(new AclRole($profile->name));
        }

        foreach ($this->privateResources as $resource => $actions) {
            $acl->addResource(new AclResource($resource), $actions);
        }

        // Grant acess to private area to role Users
        foreach ($profiles as $profile) {

            //Retrieve permissions for each profile
            $permissions = Permissions::find(array(array(
                'profile_id' => $profile->_id)));

            // Grant permissions in "permissions" model
            foreach ($permissions as $permission) {
                $acl->allow($profile->name, $permission->resource, $permission->action);
            }

            // Always grant these permissions
            $acl->allow($profile->name, 'users', 'changePassword');
        }

        if (touch(APP_DIR . $this->filePath) && is_writable(APP_DIR . $this->filePath)) {

            file_put_contents(APP_DIR . $this->filePath, serialize($acl));

            // Store the ACL in APC
            if (function_exists('apc_store')) {
                apc_store('nannyster-acl', $acl);
            }
        } else {
            $this->flash->error(
                'The user does not have write permissions to create the ACL list at ' . APP_DIR . $this->filePath
            );
        }

        return $acl;
    }
}
