<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;
use BlackoutLand\NotfallPunkt\Model\Utils;

class Chat extends Page
{
    protected $pageIndicator = 'chat';

    public function render($subPage = null)
    {
        parent::render();

        $renderer = new Renderer();
        $data     = [
            'pageIndicator' => $this->pageIndicator,
            'onlineUsers'   => Utils::getLoggedInUsers()
        ];

        parent::output($renderer->render('chat.html.twig', $data));
    }
}
