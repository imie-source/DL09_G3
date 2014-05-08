<?php
namespace Nannyster\Auth;

use Phalcon\Mvc\User\Component;
use Nannyster\Models\Users;
use Nannyster\Models\RememberTokens;
use Nannyster\Models\SuccessLogins;
use Nannyster\Models\FailedLogins;
use Nannyster\Models\Profiles;

/**
 * Manages Authentication/Identity Management in Nannyster
 */
class Auth extends Component
{

    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolan
     */
    public function check($credentials)
    {

        // Check if the user exist
        $user = Users::findFirst(array(array('login' => $credentials['login'])));
        if (!$user) {
            $this->registerUserThrottling(0);
            throw new Exception('Nous ne parvenons pas à vous authentifier! Vous avez saisi une mauvaise combinaison Email / Mot de passe!');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            $this->registerUserThrottling($user->id);
            throw  new Exception('Nous ne parvenons pas à vous authentifier! Vous avez saisi une mauvaise combinaison Email / Mot de passe!');
        }
        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            $this->createRememberEnviroment($user);
        }

        $profile = Profiles::findById(new \MongoId($user->profile_id));
        $user = get_object_vars($user);
        $user['profile_name'] = $profile->name;

        $this->session->set('auth-identity', $user);
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Vokuro\Models\Users $user
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new SuccessLogins();
        $successLogin->user_id = $user->_id;
        $successLogin->ip_address = $this->request->getClientAddress();
        $successLogin->user_agent = $this->request->getUserAgent();
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the efectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($user_id)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->user_id = $user_id;
        $failedLogin->ip_address = $this->request->getClientAddress();
        $failedLogin->attempted = time();
        $failedLogin->save();

        $attempts = FailedLogins::count(array(
            'ipAddress = ?0 AND attempted >= ?1',
            'bind' => array(
                $this->request->getClientAddress(),
                time() - 3600 * 6
            )
        ));

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Vokuro\Models\Users $user
     */
    public function createRememberEnviroment(Users $user)
    {
        $user_agent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $user_agent);

        $remember = new RememberTokens();
        $remember->users_id = $user->_id;
        $remember->token = $token;
        $remember->user_agent = $user_agent;
        if ($remember->save()) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->_id, $expire);
            if(!$this->cookies->set('RMT', $token, $expire)){
                $this->flash->warning('<i class="icon-warning-sign"></i> Votre navigateur n\'accepte pas les cookies. Vous ne pourrez donc pas être automatiquement connecté au site à chacune de vos visites');
            }
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the coookies
     *
     * @return Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $user_id = $this->cookies->get('RMU')->getValue();
        $cookie_token = $this->cookies->get('RMT')->getValue();

        $user = Users::findById($user_id);
        if ($user) {

            $user_agent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $user_agent);

            if ($cookie_token == $token) {

                $remember = RememberTokens::findFirst(array(
                    'user_id' => $user_id,
                    'token' => $token
                ));
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->created_at) {

                        // Check if the user was flagged
                        $this->checkUserFlags($user);

                        // Register identity
                        $this->session->set('auth-identity', array(
                            'id' => $user->id,
                            'name' => $user->name,
                            'profile' => $user->profile->name
                        ));

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('dashboard');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('session/login');
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param Vokuro\Models\Users $user
     */
    public function checkUserFlags(Users $user)
    {
        if ($user->active != 'Y') {
            throw new Exception('Vous n\'avez pas encore confirmé votre adresse email. Veuillez sur les instructions contenues dans l\'email reçu lors de votre inscription');
        }

        if ($user->banned != 'N') {
            throw new Exception('Votre compte utilisateur a été bannie du site Nannyster. Pour plus d\'informations, veuillez nous contacter à l\'adresse contact@nannyster.fr');
        }

        if ($user->suspended != 'N') {
            throw new Exception('Votre compte utilisateur a été suspendu. Pour plus d\'informations, veuillez nous contacter à l\'adresse contact@nannyster.fr');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current name
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Returns the current profile name
     *
     * @return string
     */
    public function getProfile()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['profile_name'];
    }

    /**
     * Returns the current id
     *
     * @return string
     */
    public function getId()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['_id'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     */
    public function authUserById($id)
    {
        $user = Users::findById($id);
        if ($user == false) {
            throw new Exception('Cet utilisateur n\'existe pas!');
        }
        $this->checkUserFlags($user);

        $profile = Profiles::findById(new \MongoId($user->profile_id));
        $user = (array) $user;
        $user['profile_name'] = $profile->name;

        $this->session->set('auth-identity', $user);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Vokuro\Models\Users
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['_id'])) {

            $user = Users::findById(new \MongoId($identity['_id']));
            if ($user == false) {
                throw new Exception('L\'utilisateur n\'existe pas');
            }

            return $user;
        }

        return false;
    }
}
