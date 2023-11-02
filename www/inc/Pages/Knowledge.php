<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class Knowledge extends Page
{
    protected $pageIndicator = 'knowledge';

    public function render()
    {
        parent::render();

        $renderer = new Renderer();
        $data     = [
            'pageIndicator'=>$this->pageIndicator
        ];

        parent::output($renderer->render('knowledge.html.twig', $data));
    }
}
