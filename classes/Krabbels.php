<?php

class Krabbels
{
  private Database $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function readAll()
  {
    $this->db->query('SELECT * FROM krabbels');
    return $this->db->resultSet();
  }

  public function read($id)
  {
    $this->db->query('SELECT * FROM krabbels WHERE krabbelId = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }
}
