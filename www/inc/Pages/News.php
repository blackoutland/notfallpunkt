<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\OwnerNews;
use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;
use Nette\Utils\Paginator;

class News extends Page
{
    protected $pageIndicator = 'news';

    public function render($subPage = null)
    {
        parent::render();

        $on        = new OwnerNews();
        $paginator = $this->getPaginator($on->getNewsCount(true), $this->settings['news_entries_per_page']);

        $renderer  = new Renderer();
        $ownerNews = $on->getAll(true, $paginator->getLength(), $paginator->getOffset());

        $prevIndex = $paginator->getPage() - 1;
        $nextIndex = $paginator->getPage() + 1;
        if ($prevIndex < 1) {
            $prevIndex = 1;
        }
        if ($nextIndex > $paginator->getPageCount()) {
            $nextIndex = $paginator->getPageCount();
        }

        $data = [
            'pageIndicator' => $this->pageIndicator,
            'news'          => $ownerNews,
            'paginator'     => [
                'pageCount'   => $paginator->getPageCount(),
                'page'        => $paginator->getPage(),
                'isFirst'     => $paginator->isFirst(),
                'isLast'      => $paginator->isLast(),
                'resultCount' => $paginator->getItemCount(),
                'prevPage'    => $prevIndex,
                'nextPage'    => $nextIndex
            ]
        ];

        parent::output($renderer->render('news.html.twig', $data));
    }
}
