<?php

namespace BlackoutLand\NotfallPunkt\Model;

class Boards
{
    /** @var Sqlite */
    private $db;

    public function __construct()
    {
        $this->db = Utils::getDb();
    }

    public function getAll($onlyPublic = true)
    {
        $params = [];
        $sql    = "SELECT * FROM boards";

        if ($onlyPublic) {
            $sql      .= ' WHERE status = ? ';
            $params[] = 'PUBLIC';
        }

        $sql .= " ORDER BY sorting";

        return $this->db->getAll($sql, $params);
    }

    public function getPostCountForBoard($boardId, $onlyPublic = true)
    {
        $params   = [];
        $sql      = "SELECT COUNT(*) AS count FROM board_posts WHERE board_id = ?";
        $params[] = (int)$boardId;

        if ($onlyPublic) {
            $sql      .= ' AND status = ? ';
            $params[] = 'PUBLIC';
        }

        return $this->db->getOneField($sql, 'count', $params);
    }

    public function getPostsForBoard($boardId, $offset = 0, $rowCount = 100, $onlyPublic = true)
    {
        $params   = [];
        $sql      = "
        SELECT
            bp.message, 
            bp.title,
            bp.status, 
            bp.ts, 
            u.login AS user_login, 
            u.status AS user_status, 
            u.info AS user_info,
            u.id AS user_id
            FROM board_posts AS bp
            LEFT OUTER JOIN users AS u ON u.id = bp.user_id
        WHERE board_id = ? ";
        $params[] = (int)$boardId;

        if ($onlyPublic) {
            $sql      .= ' AND bp.status = ? ';
            $params[] = 'PUBLIC';
        }

        $sql      .= " ORDER BY bp.id DESC LIMIT ? OFFSET ?";
        $params[] = $rowCount;
        $params[] = $offset;

        return $this->db->getAll($sql, $params);
    }
}
