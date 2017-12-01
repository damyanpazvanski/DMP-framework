<?php

namespace DMP\DatabaseManager;

class PDOMapper
{
    private $dbInstance;

    public function __construct($instance)
    {
        $this->dbInstance = $instance;
    }

    public function update($table, $update = array(), $where = '')
    {
        if (empty($update)) {
            throw new \Exception('The second parameter must be an array in format: key -> value!');
        } else {
            $updateColumns = '';

            foreach ($update as $key => $value) {
                $updateColumns .= "$key = :$key,";
            }
        }

        $updateColumns = rtrim($updateColumns, ',');
        $sql = "UPDATE $table SET $updateColumns WHERE $where";

        $stmt = $this->dbInstance->prepare($sql);

        foreach ($update as $key => $value) {
            $stmt->bindValue(':' . $key, $value, \PDO::PARAM_STR);
        }

        if ($stmt->execute() === false) {
            throw new \PDOException("The query " . addslashes($sql) . " has an error: " . $this->dbInstance->error);
        }

        return true;
    }

    public function select($table, $select = array(), $where = array(), $or = array(), $join = '')
    {
        $selectColumns = '';
        if (empty($select)) {
            $selectColumns = '*';
        } else {
            for ($i = 0, $n = count($select); $i < $n; $i++) {
                $selectColumns .= $select[$i]. ',';
            }
        }

        $selectColumns = rtrim($selectColumns, ',');
        $sql = "SELECT $selectColumns FROM $table ";

        if ($join) {
            $sql .= ' ' . $join;
        }

        if (!empty($where)) {
            if (!isset($where[0])) {
                $addWhere = '';
                foreach ($where as $key => $value) {
                    $rkey = str_replace('.', '', $key);
                    $addWhere .= "$key = :$rkey AND ";
                }

                $addWhere = substr($addWhere, 0, -5);
            } else {
                $addWhere = $where[0];
            }

            if (!empty($or)) {
                if (!isset($or[0])) {
                    foreach ($or as $key => $value) {
                        $rkey = str_replace('.', '', $key);
                        $addWhere .= " OR $key = :$rkey";
                    }
                } else {
                    $addWhere .= $or[0];
                }
            }

            $sql .= ' WHERE ' . $addWhere;
        }

        $stmt = $this->dbInstance->prepare($sql);

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $rkey = str_replace('.', '', $key);
                $stmt->bindValue(':' . $rkey, $value, \PDO::PARAM_STR);
            }

            if (!empty($or)) {
                foreach ($or as $key => $value) {
                    $rkey = str_replace('.', '', $key);
                    $stmt->bindValue(':' . $rkey, $value, \PDO::PARAM_STR);
                }
            }
        }

        if (!$stmt->execute()) {
            throw new \PDOException("The query " . addslashes($sql) . " has an error: " . $this->dbInstance->error);
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $return = array();
        while (($notification = $stmt->fetch()) !== false) {
            $return[] = $notification;
        }

        $stmt = NULL;

        return $return;
    }

    public function insert($table, $params)
    {
        if (empty($params)) {
            throw new \Exception('The second parameter must be an array in format: key -> value!');
        } else {
            $keys = "";
            $values = "";

            foreach ($params as $key => $value) {
                $keys .= "$key,";
                $values .= ":$key,";
            }
        }

        $keys = rtrim($keys, ',');
        $values = rtrim($values, ',');

        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        $stmt = $this->dbInstance->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, \PDO::PARAM_STR);
        }

        if ($stmt->execute() === false) {
            throw new \PDOException("The query " . addslashes($sql) . " has an error: " . $this->dbInstance->error);
        }

        return true;
    }

    public function delete($table, $where = array(), $or = array())
    {
        if (empty($where)) {
            throw new \Exception('The second parameter must be an array in format: key -> value!');
        } else {

            $addWhere = '';
            foreach ($where as $key => $value) {
                $rkey = str_replace('.', '', $key);
                $addWhere .= "$key = :$rkey AND ";
            }
        }

        $addWhere = substr($addWhere, 0, -5);

        if (!empty($or)) {
            foreach ($or as $key => $value) {
                $rkey = str_replace('.', '', $key);
                $addWhere .= " OR $key = :$rkey";
            }
        }

        $sql = "DELETE FROM $table WHERE $addWhere";

        $stmt = $this->dbInstance->prepare($sql);

        foreach ($where as $key => $value) {
            $rkey = str_replace('.', '', $key);
            $stmt->bindValue(':' . $rkey, $value, \PDO::PARAM_STR);
        }

        if (!empty($or)) {
            foreach ($or as $key => $value) {
                $rkey = str_replace('.', '', $key);
                $stmt->bindValue(':' . $rkey, $value, \PDO::PARAM_STR);
            }
        }

        if ($stmt->execute() === false) {
            throw new \PDOException("The query " . addslashes($sql) . " has an error: " . $this->dbInstance->error);
        }

        return true;
    }

    public function selectResource($table, $select = array(), $where = array(), $or = array(), $join = '')
    {
        $selectColumns = '';
        if (empty($select)) {
            $selectColumns = '*';
        } else {
            for ($i = 0, $n = count($select); $i < $n; $i++) {
                $selectColumns .= $select[$i]. ',';
            }
        }

        $selectColumns = rtrim($selectColumns, ',');
        $sql = "SELECT $selectColumns FROM $table ";

        if ($join) {
            $sql .= ' ' . $join;
        }

        if (!empty($where)) {
            if (!isset($where[0])) {
                $addWhere = '';
                foreach ($where as $key => $value) {
                    $rkey = str_replace('.', '', $key);
                    $addWhere .= "$key = :$rkey AND ";
                }

                $addWhere = substr($addWhere, 0, -5);
            } else {
                $addWhere = $where[0];
            }

            if (!empty($or)) {
                if (!isset($or[0])) {
                    foreach ($or as $key => $value) {
                        $rkey = str_replace('.', '', $key);
                        $addWhere .= " OR $key = :$rkey";
                    }
                } else {
                    $addWhere .= $or[0];
                }
            }

            $sql .= ' WHERE ' . $addWhere;
        }

        $stmt = $this->dbInstance->prepare($sql);

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $rkey = str_replace('.', '', $key);
                $stmt->bindValue(':' . $rkey, $value, \PDO::PARAM_STR);
            }

            if (!empty($or)) {
                foreach ($or as $key => $value) {
                    $rkey = str_replace('.', '', $key);
                    $stmt->bindValue(':' . $rkey, $value, \PDO::PARAM_STR);
                }
            }
        }

        if (!$stmt->execute()) {
            throw new \PDOException("The query " . addslashes($sql) . " has an error: " . $this->dbInstance->error);
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        return $stmt;
    }
}