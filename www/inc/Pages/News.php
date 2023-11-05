<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\OwnerNews;
use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;
use Nette\Utils\Paginator;

class News extends Page
{
    protected $pageIndicator = 'news';

    public function render()
    {
        parent::render();

        $on        = new OwnerNews();
        $paginator = $this->getPaginator($on->getNewsCount(true), $this->settings['news_entries_per_page']);

        /*
        $paginator->isFirst(); // is this the first page?
        $paginator->isLast(); // is this the last page?
        $paginator->getPage(); // current page number
        $paginator->getFirstPage(); // the first page number
        $paginator->getLastPage(); // the last page number
        $paginator->getFirstItemOnPage(); // sequence number of the first item on the page
        $paginator->getLastItemOnPage(); // sequence number of the last item on the page
        $paginator->getPageIndex(); // current page number if numbered from 0
        $paginator->getPageCount(); // the total number of pages
        $paginator->getItemsPerPage(); // the number of records per page
        $paginator->getItemCount(); // the total number of records (if available)

        $paginator->getLength(),
	    $paginator->getOffset(),
        */

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
