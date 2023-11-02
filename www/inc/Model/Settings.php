<?php

namespace BlackoutLand\NotfallPunkt\Model;

class Settings
{
    /** @var Sqlite */
    private $db;

    public function __construct()
    {
        $this->db = Utils::getDb();
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $params = [];
        $sql    = "SELECT * FROM settings";
        $rows   = $this->db->getAll($sql, $params);

        $results = [];
        foreach ($rows as $row) {
            $results[$row['name']] = $row['value'];
        }
        return $results;
    }
}
