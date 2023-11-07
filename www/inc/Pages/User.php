<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\MemcacheConnection;
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
            // TODO: Show all users for admins
            $userCount         = $um->getUserCount(true);
            $paginator         = $this->getPaginator($userCount, $this->settings['user_profiles_per_page']);
            $data['users']     = $um->getAll(true, $paginator->getLength(), $paginator->getOffset());
            $data['userCount'] = $userCount;

            // Get online status
            // TODO: Only do if memcache enabled
            $userOnlineStatus = [];
            foreach ($data['users'] as $usr) {
                $userOnlineStatus[$usr['login']] = (bool)Utils::memcacheGet('usr.' . $usr['login'] . '.online');
            }
            $data['userOnlineStatuses'] = $userOnlineStatus;
        }

        if ($subPage === 'signup') {
            // ... den [Nutzungsbedingungen] zu ...
            $termsLink = $this->settings['user_signup_form_confirm_terms'];
            if (preg_match("/(.*)\[(.*)\](.*)/", $termsLink, $m)) {
                $data['termsLink'] = $m[1] . '<a href="/terms">' . $m[2] . '</a>' . $m[3];
            }
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
