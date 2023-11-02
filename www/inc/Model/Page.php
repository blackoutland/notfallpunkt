<?php

namespace BlackoutLand\NotfallPunkt\Model;

class Page
{
    protected $isDynamic = false;
    protected $cacheId = 'DEFAULT';

    protected $pageIndicator = null;

    private function getCacheFileName()
    {
        return '/temp/pagecache_' . $this->pageIndicator . '_' . $this->cacheId . '.cache';
    }

    public function render()
    {
        $cacheFile = $this->getCacheFileName();
        if (file_exists($cacheFile)) {
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

    public function output($output)
    {
        file_put_contents($this->getCacheFileName(), $output);
        $output = str_replace('%%CACHE_INFO%%', '(fresh)', $output);
        echo $output;
    }
}
