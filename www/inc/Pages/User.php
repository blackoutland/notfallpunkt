<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class User extends Page
{
    protected $pageIndicator = 'user';

    public function render($subPage = null)
    {
        parent::render();

        $renderer = new Renderer();
        $data     = [
            'pageIndicator' => $this->pageIndicator
        ];

        parent::output($renderer->render('user.' . $subPage . '.html.twig', $data));
    }

    public function logout()
    {
        die("TODO: LOGOUT");
    }
}
