<?php

namespace BlackoutLand\NotfallPunkt\Model;

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

    public function __construct()
    {
        $this->settings = Utils::getSettings();
    }

    private function getCacheFileName()
    {
        return '/temp/pagecache_' . $this->pageIndicator . '_' . $this->cacheId . '.cache';
    }

    public function render()
    {
        $cacheFile = $this->getCacheFileName();
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

    public function output($output)
    {
        if ($GLOBALS['config']['disableCache']) {
            $output = str_replace('%%CACHE_INFO%%', '(fresh, cache disabled)', $output);
        } else {
            file_put_contents($this->getCacheFileName(), $output);
            $output = str_replace('%%CACHE_INFO%%', '(fresh)', $output);
        }

        echo $output;
    }
}
