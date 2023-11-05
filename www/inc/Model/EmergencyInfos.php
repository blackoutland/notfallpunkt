<?php

namespace BlackoutLand\NotfallPunkt\Model;

class EmergencyInfos
{
    /** @var Sqlite */
    private $db;

    public function __construct()
    {
        $this->db = Utils::getDb();
    }

    public function getAll()
    {
        $params = [];
        $sql    = "SELECT * FROM emergency_infos ORDER BY sorting";

        $rows = $this->db->getAll($sql, []);

        $results = [];
        $titles  = [];
        $values  = [];
        foreach ($rows as $row) {
            if (preg_match("/^title_(.*)/", $row['name'], $m)) {
                $titles[$m[1]] = $row['value'];
            } else {
                $values[$row['name']] = $row['value'];
            }
        }
        foreach ($titles as $name => $value) {
            $results[] = [
                'name'  => $name,
                'title' => $value,
                'value' => $values[$name]
            ];
        }

        return $results;
    }
}
