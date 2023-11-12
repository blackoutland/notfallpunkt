<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\ChatManager;
use BlackoutLand\NotfallPunkt\Model\MemcacheConnection;
use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;
use BlackoutLand\NotfallPunkt\Model\UserManager;
use BlackoutLand\NotfallPunkt\Model\Utils;
use Exception;

class Ajax extends Page
{
    protected $pageIndicator = 'ajax';

    public function board()
    {
        dump($_POST);
    }

    public function status()
    {
        $settings      = Utils::getSettings();
        $loginUserData = $GLOBALS['UserManager']->getLoggedInUserData();

        // Log to Memcache
        if ($loginUserData) {
            try {
                Utils::memcacheSet("usr." . $loginUserData['login'] . '.online', 1, 60); // TODO: Allow configuration of expiration
            } catch (Exception $e) {
                // Ignore silently
            }
        }

        $userCountOnline = count(Utils::getLoggedInUsers());
        $onlineUsers     = Utils::getLoggedInUsers();

        $chatMessages = [];
        if ($_POST['chat']) {
            $lastChatMessageId = empty($_POST['cmsgid']) ? 0 : (int)$_POST['cmsgid'];
            $cm                = new ChatManager();
            $chatMessages      = $cm->getMessagesSince($lastChatMessageId);
        }

        header("Content-Type: application/json");

        echo json_encode(
            [
                'success'      => true,
                'isTestMode'   => (bool)$settings['is_test_mode'],
                'user'         => [
                    'isLoggedIn' => !!$loginUserData,
                    'userName'   => $loginUserData ? $loginUserData['login'] : null
                ],
                "updates"      => [
                    // TODO: Whenever someone updates news etc. send message
                ],
                "updateCounts" => [
                    "home"       => 0,
                    "news"       => 0,
                    "board"      => 0,
                    "files"      => 0,
                    "knowledge"  => 0,
                    "users"      => 0,
                    "userOnline" => $userCountOnline
                ],
                "chatMessages" => $chatMessages,
                "onlineUsers"  => $onlineUsers
            ]
        );

    }

    public function chat()
    {
        $loginUserData = $GLOBALS['UserManager']->getLoggedInUserData();


        if (!Utils::isLoggedIn()) {
            header("HTTP/1.1 403 Forbidden");
            header("Content-Type: application/json");
            echo json_encode(
                [
                    "success" => false,
                    "id"      => $_POST['id']
                ],
                JSON_PRETTY_PRINT);
            exit;
        }

        $expiration       = 3600; // TODO: make configurable!
        $cm               = new ChatManager();
        $maxMessageLength = 256; // TODO: Make configurable and use for inputs as well!
        $cm->addMessage(Utils::getLoggedInUser()['login'], $_POST['id'], $_POST['msg'], $expiration);

        die("OK");
    }

}
