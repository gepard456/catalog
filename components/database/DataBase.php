<?php

class DataBase
{
    public $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll($table)
    {
        $sql = "SELECT * FROM $table";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getOne($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE `id` = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $sql = "INSERT INTO $table (" . implode(',',$keys) . ") VALUES (:" . implode(',:',$keys) . ")";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
    }

    public function update($table, $data, $id)
    {
        $sql = "UPDATE $table SET";
        $toggle = true;

        foreach($data as $key => $value)
        {
            if($toggle)
            {
                $sql .= " $key=:$key";
                $toggle = false;
            }
            else
                $sql .= ",$key=:$key";
        }

        $sql .= " WHERE id = :id";
        $data["id"] = $id;
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
    }

    public function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}

$pdo = new PDO("mysql:host=localhost; dbname=catalog", "root", "");

$dataBase = new DataBase($pdo);