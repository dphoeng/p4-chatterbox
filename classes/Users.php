<?php

class Users
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function readAll()
  {
    $this->db->query('SELECT * FROM users');
    return $this->db->resultSet();
  }

  public function read($id)
  {
    $this->db->query('SELECT * FROM users WHERE usersId = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }
}
