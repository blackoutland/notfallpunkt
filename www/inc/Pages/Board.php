<?php

namespace BlackoutLand\NotfallPunkt\Pages;

use BlackoutLand\NotfallPunkt\Model\Boards;
use BlackoutLand\NotfallPunkt\Model\Page;
use BlackoutLand\NotfallPunkt\Model\Renderer;

class Board extends Page
{
    protected $pageIndicator = 'board';

    public function render($subPage = null)
    {
        parent::render();

        $bm     = new Boards();
        $boards = $bm->getAll(true);

        // TODO: Admin should see more!
        $onlyPublicPosts = true;

        $offset = 0; // TODO: implement paging?

        $postData = [];
        foreach ($boards as $board) {
            $postCount = (int)$bm->getPostCountForBoard($board['id'], $onlyPublicPosts);
            if ($postCount) {
                $posts = $bm->getPostsForBoard($board['id'], $offset, $this->settings['board_list_postcount'], $onlyPublicPosts);
            } else {
                $posts = [];
            }

            $postData[] = [
                'board' => $board,
                'posts' => $posts,
                'count' => $postCount
            ];
        }

        $renderer = new Renderer();
        $data     = [
            'pageIndicator' => $this->pageIndicator,
            'boards'      => $postData
        ];

        parent::output($renderer->render('board.html.twig', $data));
    }
}
