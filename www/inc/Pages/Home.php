<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\GeneralInfos;
use BlackoutLand\NotfallPunkt\Model\OwnerNews;
use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class Home extends Page
{
    protected $pageIndicator = 'home';

    public function render($subPage = null)
    {
        if ($subPage === 'notfound') {
            header("HTTP/1.1 404 Not Found");
        }

        parent::render($subPage);

        if ($subPage === 'notfound') {
            $renderer = new Renderer();
            $data     = [
                'path'   => $_SERVER['REQUEST_URI'],
                'params' => str_replace('_page='.$_GET['_page'].'&', '', $_SERVER['QUERY_STRING'])
            ];

            parent::output($renderer->render('404.html.twig', $data), true);
        }

        // Get data
        $on              = new OwnerNews();
        $newsCount       = $on->getNewsCount(true);
        $newsToShowCount = $this->settings['home_newslist_count'];
        $paginator       = $this->getPaginator($newsCount, $newsToShowCount);
        $ownerNews       = $on->getAll(true, $paginator->getLength(), $paginator->getOffset());

        $in    = new GeneralInfos();
        $infos = $in->getAll();

        $renderer = new Renderer();
        $data     = [
            'more_news_available' => $newsCount > $newsToShowCount,
            'news_owner'          => $ownerNews,
            'infos'               => $infos,
            'pageIndicator'       => $this->pageIndicator
        ];

        parent::output($renderer->render('index.html.twig', $data));
    }
}
