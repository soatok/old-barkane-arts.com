<?php
declare(strict_types=1);
namespace BarkaneArts\Website\Controller\Prototypes;

use BarkaneArts\Framework\Controller;
use ParagonIE\Cookie\Session;

/**
 * Class LoggedInUsersOnly
 *
 * Instead of extending from Controller, extend from this class.
 * It enforces user authentication.
 *
 * @package BarkaneArts\Website\Controller\Prototypes
 */
class LoggedInUsersOnly extends Controller
{
    /**
     * If the user is not logged in, redirect ot the login form and exit.
     *
     */
    public function beforeRoute()
    {
        if (!Session::get('userid')) {
            \BarkaneArts\redirect('/login');
        }
        parent::beforeRoute();
    }
}
