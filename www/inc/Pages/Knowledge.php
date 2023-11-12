<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;
use BlackoutLand\NotfallPunkt\Model\Utils;

class Knowledge extends Page
{
    protected $pageIndicator = 'knowledge';

    public function render($subPage = null)
    {
        parent::render();

        $knowledgeInfosByCategory = [];
        $fileInfos                = Utils::getFileInfo();
        foreach ($fileInfos['knowledge'] as $fileInfo) {
            if (!isset($knowledgeInfosByCategory[$fileInfo['category']])) {
                $knowledgeInfosByCategory[$fileInfo['category']] = [];
            }
            $knowledgeInfosByCategory[$fileInfo['category']][] = $fileInfo;
        }

        $renderer = new Renderer();
        $data     = [
            'hostname'                 => $_SERVER['HTTP_HOST'],
            'kiwixPort'                => $GLOBALS['config']['components']['kiwix']['port'],
            'fileshare'                => $fileInfos,
            'knowledgeInfosByCategory' => $knowledgeInfosByCategory,
            'pageIndicator'            => $this->pageIndicator
        ];

        parent::output($renderer->render('knowledge.html.twig', $data));
    }
}
