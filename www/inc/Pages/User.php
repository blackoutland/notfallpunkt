<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;
use BlackoutLand\NotfallPunkt\Model\UserManager;
use BlackoutLand\NotfallPunkt\Model\Utils;

class User extends Page
{
    protected $pageIndicator = 'user';

    public function render($subPage = null)
    {
        parent::render();

        if ($subPage === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->login($_POST);
        }


        $renderer = new Renderer();
        $data     = [
            'pageIndicator' => $this->pageIndicator
        ];

        if ($subPage === 'login') {
            $data['error'] = empty($_GET['error']) ? null : $_GET['error'];
            $data['login'] = empty($_GET['login']) ? null : $_GET['login'];
        }

        if ($subPage === 'list') {
            $um = new UserManager();
            $data['users'] = $um->getAll(true);
        }

        parent::output($renderer->render('user.' . $subPage . '.html.twig', $data));
    }

    public function logout()
    {
        $um = new UserManager();
        $um->logout();
    }

    public function login($postData)
    {
        $um = new UserManager();
        if (empty($postData['user']) || empty($postData['pass'])) {
            Utils::redirectTo('/login', 302);
        }

        // Not supported yet
        if (empty($postData['persistent'])) {
            $postData['persistent'] = false;
        }
        list($success, $error, $userData) = $um->login($postData['user'], $postData['pass'], $postData['persistent']);

        if ($success) {
            $GLOBALS['UserManager']->setLoginUserData($userData);
            Utils::redirectTo('/user/profile');
        }

        Utils::redirectTo('/login?error=1&login=' . $postData['user']);
    }
}
