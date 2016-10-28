<?php
declare(strict_types=1);
namespace BarkaneArts\Website\Controller;

use BarkaneArts\Website\Controller\Prototypes\LoggedInUsersOnly;

/**
 * Class Dashboard
 * @package BarkaneArts\Website\Controller
 */
class Dashboard extends LoggedInUsersOnly
{
    /**
     *
     */
    public function index()
    {
        $this->view('gateway/index');
    }
}
