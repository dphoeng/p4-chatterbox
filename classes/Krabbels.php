<?php

class Krabbels
{
  private $db;

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

  public function getPosts($id, $isArray)
  {
    if ($id === "all")
      $this->db->query("SELECT * FROM `krabbels` WHERE attachedToId IS NULL ORDER BY `postDate` desc");
    else if ($isArray) {
      $this->db->query("SELECT * FROM `krabbels` WHERE `profileId` in (:id) AND attachedToId IS NULL ORDER BY `postDate` desc");
      $this->db->bind(':id', $id);
    } else {
      $this->db->query("SELECT * FROM `krabbels` WHERE `profileId` = :id AND attachedToId IS NULL ORDER BY `postDate` desc");
      $this->db->bind(':id', $id);
    }
    $result = $this->db->resultSet();
    $posts = "";
    foreach ($result as $key => $krabbel) {
      // echo $key . ": " . $krabbel . "<br>";
      $this->db->query("SELECT * FROM `users` WHERE `usersId` = :id");
      $this->db->bind(':id', $krabbel->posterId);
      $user = $this->db->single();
      $image = "";
      if ($krabbel->image) {
        $image = "<div class='ml-auto'>
              <img class='full-height' src='{$krabbel->image}' alt='post img'>
                </div>";
      }
      $delete = "";
      if ($krabbel->posterId == $_SESSION['id']) {
        $delete = "<a href='/index.php?content=createUpdate&profiel={$krabbel->posterId}&krabbelId={$krabbel->krabbelId}' class='delete-post'>Delete</a>";
      }
      $posts .= "<main class='side-main-content'>
      <div class='post' data-post-id='1'>
        <header>
        <a href='./index.php?content=profiel/{$user->usersId}'>
          <img class='icon-rounded medium' src='{$user->avatar}' alt='profile img'>
        </a>
        <div>
          <h4>
            {$user->nickname}
          </h4>
          <h5>
            {$krabbel->postDate}
          </h5>
        </div>
        " . $delete . "
        </header>
        <main>
        <p>{$krabbel->text}</p>
        {$image}
        </main>
        <hr>
        <footer>
        <div>
          <a class='hover rounded medium' href='./index.php?content=createUpdate&profiel={$krabbel->profileId}&attached={$krabbel->krabbelId}'><button class='hover rounded medium'>
          comment
          </button></a>
          <button class='hover rounded medium'>
          show comments
          </button>
        </footer>
      </div>
      </main>";
    }
    return $posts;
  }
}
