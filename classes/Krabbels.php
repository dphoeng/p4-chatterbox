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
        $image = "<div>
              <img class='full-height' src='{$krabbel->image}' alt='post img'>
                </div>";
      }
      $delete = "";
      if ($krabbel->posterId == $_SESSION['id']) {
        $delete = "<a class='ml-auto' href='/index.php?content=createUpdate&profiel={$krabbel->posterId}&krabbelId={$krabbel->krabbelId}' class='delete-post'>
        <svg width='40' height='41' viewBox='0 0 40 41' fill='none' xmlns='http://www.w3.org/2000/svg'>
<path d='M14 18.4218C12.9 18.4218 12 19.3218 12 20.4218C12 21.5218 12.9 22.4218 14 22.4218C15.1 22.4218 16 21.5218 16 20.4218C16 19.3218 15.1 18.4218 14 18.4218ZM26 18.4218C24.9 18.4218 24 19.3218 24 20.4218C24 21.5218 24.9 22.4218 26 22.4218C27.1 22.4218 28 21.5218 28 20.4218C28 19.3218 27.1 18.4218 26 18.4218ZM20 18.4218C18.9 18.4218 18 19.3218 18 20.4218C18 21.5218 18.9 22.4218 20 22.4218C21.1 22.4218 22 21.5218 22 20.4218C22 19.3218 21.1 18.4218 20 18.4218Z' fill='white'/>
</svg></a>";
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
