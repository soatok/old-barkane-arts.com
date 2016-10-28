<?php
declare(strict_types=1);
namespace BarkaneArts\Website\Controller;

use BarkaneArts\Framework\Alerts\UserAuthenticationFailed;
use BarkaneArts\Framework\Controller;
use BarkaneArts\Website\InputFilters\{
    LoginFilter,
    RegisterFilter
};
use BarkaneArts\Website\Model\User;
use ParagonIE\Cookie\Session;
use ParagonIE\Halite\HiddenString;

/**
 * Class Gateway
 * @package BarkaneArts\Website\Controller
 */
class Gateway extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * Post-constructor.
     */
    public function beforeRoute()
    {
        parent::beforeRoute();
        $this->user = $this->model('User');
    }

    /**
     *
     */
    public function login()
    {
        if (Session::get('userid')) {
            \BarkaneArts\redirect('/dashboard');
        }
        $post = $this->getPostData(new LoginFilter());
        if ($post) {
            try {
                $userData = $this->user->login(
                    $post['username'],
                    new HiddenString($post['passphrase'])
                );
                $_SESSION['userid'] = (int) $userData['userid'];
                \BarkaneArts\redirect('/');
            } catch (UserAuthenticationFailed $ex) {
                \BarkaneArts\redirect('/login');
            }
        }
        $this->view('gateway/login');
    }

    /**
     * Log the user out
     *
     * @todo mitigate Logout CSRF
     */
    public function logout()
    {
        Session::delete('userid');
        \BarkaneArts\redirect('/login');
    }


    /**
     *
     */
    public function register()
    {
        $post = $this->getPostData(new RegisterFilter());
        if ($post) {
            if ($this->user->create($post)) {
                \BarkaneArts\redirect('/');
            }
        }
        $this->view('gateway/register');
    }
}
