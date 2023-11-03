<?php

namespace BlackoutLand\NotfallPunkt\Model;

use PDO;
use PDOException;
use RuntimeException;
use SQLite3;
use UnexpectedValueException;

class Sqlite
{
    /** @var PDO */
    private $db;

    public function __construct($dbName)
    {
        $db = null;
        try {
            $db = new PDO('sqlite:/db/' . $dbName);
        } catch (PDOException $e) {
            if (preg_match("/Could not find driver/i", $e->getMessage())) {
                echo "<h1>PDO-Treiber fehlt in PHP-Version!</h1>";
                exit(1);
            }
        }
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db = $db;
    }

    /**
     * Version number in INT format
     *
     * @return int
     */
    public function getVersionNumber()
    {
        $versionData = SQLite3::version();

        return $versionData['versionNumber'];
    }

    /**
     * Upsert is only supported from 3.24.0 onwards
     *
     * @return bool
     * @see https://www.sqlite.org/lang_upsert.html
     */
    public function supportsUpsert()
    {
        return ($this->getVersionNumber() >= 3024000);
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    private function tableExists($name)
    {
        $sth = $this->db->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name=?");
        if (!$sth->execute([$name])) {
            throw new \UnexpectedValueException('Table check failed for table "' . $name . '"!');
        }

        return (bool)$sth->fetchColumn(0);
    }

    public function close()
    {
        if ($this->db) {
            $this->db = null;
        }
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollBack();
    }

    /**
     * @param string $sql
     *
     * @return false|\PDOStatement
     */
    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function getOneField($sql, $fieldName, array $params = [])
    {
        $sth = $this->preparedQuery($sql, $params);
        if (!$sth->columnCount()) {
            return null;
        }

        $row = $sth->fetch(PDO::FETCH_ASSOC);
        if (!empty($row[$fieldName])) {
            return $row[$fieldName];
        }
        return null;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function getAll($sql, array $params = [])
    {
        $sth = $this->preparedQuery($sql, $params);
        if (!$sth->columnCount()) {
            return [];
        }

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return bool|\PDOStatement
     */
    public function preparedQuery($sql, array $params)
    {
        if ($GLOBALS['config']['debugSql']) {
            echo "<div class=\"npdebug sql\">[SQL:" . basename(__METHOD__) . "] SQL='$sql', Params=" . implode(", ", $params) . "]</div>";
        }
        $sth = $this->db->prepare($sql);
        $sth->execute($params);

        return $sth;
    }

    /**
     * @param string $name
     * @param bool $ignoreNonExisting
     * @param bool $returnHidden
     *
     * @return string
     */
    public function getSetting($name, $ignoreNonExisting = false)
    {
        // Query first
        $sth = $this->db->prepare('SELECT COUNT(*) AS count FROM settings WHERE name = ?');
        $sth->execute([$name]);
        $count = (int)$sth->fetchColumn(0);

        if (!$count) {
            if ($ignoreNonExisting) {
                return null;
            }
            throw new \UnexpectedValueException('Did not find setting "' . $name . '" in database!');
        }

        $sth = $this->db->prepare('SELECT value FROM settings WHERE name = ?');
        $sth->execute([$name]);

        return $sth->fetchColumn(0);
    }

    /**
     * @param string $string
     *
     * @return false|string
     */
    public function quote($string)
    {
        return $this->db->quote($string);
    }
}