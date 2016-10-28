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
            // You're already logged in!
            \BarkaneArts\redirect('/dashboard');
        }

        $post = $this->getPostData(new LoginFilter());
        if (!empty($post['username']) && !empty($post['passphrase'])) {
            // POST data was provided. Let's attempt to login.
            try {
                $userData = $this->user->login(
                    $post['username'],
                    new HiddenString($post['passphrase'])
                );

                // If an exception wasn't thrown, it returned data...
                $_SESSION['userid'] = (int) $userData['userid'];

                // Redirect to the homepage.
                \BarkaneArts\redirect('/');
            } catch (UserAuthenticationFailed $ex) {
                // @todo log the failure here.
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
