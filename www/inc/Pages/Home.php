<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\GeneralInfos;
use BlackoutLand\NotfallPunkt\Model\OwnerNews;
use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class Home extends Page
{
    protected $pageIndicator = 'home';

    public function render()
    {
        parent::render();

        // Get data
        $on        = new OwnerNews();
        $ownerNews = $on->getAll(true);

        $in    = new GeneralInfos();
        $infos = $in->getAll();

        $renderer = new Renderer();
        $data     = [
            'news_owner'    => $ownerNews,
            'infos'         => $infos,
            'pageIndicator' => $this->pageIndicator
        ];

        parent::output($renderer->render('index.html.twig', $data));
    }
}
