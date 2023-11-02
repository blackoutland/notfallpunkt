<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class Board extends Page
{
    protected $pageIndicator = 'board';

    public function render()
    {
        parent::render();

        $renderer = new Renderer();
        $data     = [
            'pageIndicator'=>$this->pageIndicator
        ];

        parent::output($renderer->render('board.html.twig', $data));
    }
}
