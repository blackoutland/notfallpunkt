<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class News extends Page
{
    protected $pageIndicator = 'news';

    public function render()
    {
        parent::render();

        $renderer = new Renderer();
        $data     = [
            'pageIndicator' => $this->pageIndicator
        ];

        parent::output($renderer->render('news.html.twig', $data));
    }
}
