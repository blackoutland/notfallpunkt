<?php

namespace BlackoutLand\NotfallPunkt\Model;

use Nette\Utils\Paginator;

class Page
{
    /**
     * @var bool
     */
    protected $isDynamic = false;

    /**
     * @var string
     */
    protected $cacheId = 'DEFAULT';

    /**
     * @var string
     */
    protected $pageIndicator = null;

    /**
     * @var array
     */
    protected $settings = [];

    protected $cacheRelatedQueryParams = [];

    public function __construct()
    {
        $this->settings = Utils::getSettings();
    }

    private function getCacheFileName($subPage = null)
    {
        // Consider query params for caching!
        $paramString = '';
        $params      = [];
        if (count($this->cacheRelatedQueryParams)) {
            foreach ($this->cacheRelatedQueryParams as $str) {
                if (!empty($_GET[$str])) {
                    $params[] = $str . '-' . $_GET[$str];
                }
            }
            $paramString = '-' . md5(implode('|', $params));
        }

        return '/temp/pagecache_' . $this->pageIndicator . '_' . $this->cacheId . $subPage . $paramString . '.cache';
    }

    public function render($subPage = null)
    {
        $cacheFile = $this->getCacheFileName($subPage);
        if (file_exists($cacheFile) && !$GLOBALS['config']['disableCache']) {
            header("Content-Type: text/html");
            $content = file_get_contents($cacheFile);
            $content = str_replace('%%CACHE_INFO%%', '(from cache)', $content);
            echo $content;
            exit;
        }
    }

    public function clearCache()
    {

    }

    public function getPageIndicator()
    {
        return $this->pageIndicator;
    }

    public function output($output, $exit = false)
    {
        if ($GLOBALS['config']['disableCache']) {
            $output = str_replace('%%CACHE_INFO%%', '(fresh, cache disabled)', $output);
        } else {
            file_put_contents($this->getCacheFileName(), $output);
            $output = str_replace('%%CACHE_INFO%%', '(fresh)', $output);
        }

        echo $output;

        if ($exit) {
            exit();
        }
    }


    /**
     * @param int $totalCount
     * @return Paginator
     */
    public function getPaginator($totalCount, $itemsPerPage)
    {

        $currentPage = 1;
        if (!empty($_GET['page'])) {
            $currentPage = (int)$_GET['page'];
            if (!$currentPage) {
                $currentPage = 1;
            }
        }

        $paginator = new Paginator;
        $paginator->setPage($currentPage); // the number of the current page (numbered from 1)
        $paginator->setItemsPerPage($itemsPerPage); // the number of records per page
        $paginator->setItemCount($totalCount); // the total number of records (if available)

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

        return $paginator;
    }
}
