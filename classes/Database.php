<?php

class Database
{
  private $Db_host = DB_HOST;
  private $Db_name = DB_NAME;
  private $Db_user = DB_USER;
  private $Db_pass = DB_PASS;
  private $Db_handler;
  private $stmt;

  public function __construct()
  {
    $conn = "mysql:host=$this->Db_host;dbname=$this->Db_name;charset=UTF8";
    try {
      $this->Db_handler = new PDO($conn, $this->Db_user, $this->Db_pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  PDO::ATTR_PERSISTENT => true]);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function query($sql)
  {
    $this->stmt = $this->Db_handler->prepare($sql);
    // var_dump($this->stmt);
  }

  public function bind($param, $value, $type = null)
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
  }

  public function execute()
  {
    $this->stmt->execute();
  }

  public function resultSet()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function single()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  public function rowCount()
  {
    return $this->stmt->rowCount();
  }
}
