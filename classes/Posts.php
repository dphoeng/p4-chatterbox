<?php

class Posts
{
  private Database $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function readAll()
  {
    $this->db->query('SELECT * FROM posts');
    return $this->db->resultSet();
  }

  public function read($id)
  {
    $this->db->query('SELECT * FROM posts WHERE id = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }
}
