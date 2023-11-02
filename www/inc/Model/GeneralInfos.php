<?php

namespace BlackoutLand\NotfallPunkt\Model;

class GeneralInfos
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
        $sql    = "SELECT * FROM general_infos";

        $sql      .= " ORDER BY sorting";

        return $this->db->getAll($sql, []);
    }
}
