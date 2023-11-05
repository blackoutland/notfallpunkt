<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class Files extends Page
{
    protected $pageIndicator = 'files';

    public function render($subPage = null)
    {
        parent::render();

        $renderer = new Renderer();
        $data     = [
            'pageIndicator'=>$this->pageIndicator
        ];

        parent::output($renderer->render('files.html.twig', $data));
    }
}
