<?php

namespace BlackoutLand\NotfallPunkt\Model;

class LocalizationManager
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
        $sql    = "SELECT * FROM localizations";
        $rows   = $this->db->getAll($sql, $params);

        $results = [];
        foreach ($rows as $row) {
            $results[$row['name']] = $row['value'];
        }
        return $results;
    }
}
