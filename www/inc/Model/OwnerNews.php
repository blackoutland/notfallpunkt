<?php

namespace BlackoutLand\NotfallPunkt\Model;

class OwnerNews
{
    /** @var Sqlite */
    private $db;

    public function __construct()
    {
        $this->db = Utils::getDb();
    }

    public function getAll($onlyPublic = true, $rowCount = 100, $startIndex = 0)
    {
        $params = [];
        $sql    = "SELECT * FROM news_owner";

        if ($onlyPublic) {
            $sql      .= " WHERE status = ? ";
            $params[] = 'PUBLIC';
        }

        $sql      .= " ORDER BY ts DESC LIMIT ? OFFSET ?";
        $params[] = $rowCount;
        $params[] = $startIndex;

        return $this->db->getAll($sql, $params);
    }

    public function getNewsCount($onlyPublic = true)
    {
        $params   = [];
        $sql      = "SELECT COUNT(*) AS count FROM news_owner";

        if ($onlyPublic) {
            $sql      .= ' WHERE status = ? ';
            $params[] = 'PUBLIC';
        }

        return $this->db->getOneField($sql, 'count', $params);
    }
}
