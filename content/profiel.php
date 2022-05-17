<?php

$sql = "SELECT nickname, birthday,respect,bio,avatar,friends,background FROM users WHERE usersId = {$_GET['id']}";
$result = mysqli_query($conn, $sql);
$record = mysqli_fetch_assoc($result);
// var_dump($record);

?>

<main class="profiel-page profiel-background">
  <style>
    .profiel-background {
      background: url(".<?php echo $record['background']; ?>");
    }
  </style>
  <div class="profiel">
    <div class="info">
      <div class="iconLarge profiel-icon">
        <img src="../src/img/Logo.png" alt="">
      </div>
      <div class="profiel-naam">
        <h3><?php echo $record['nickname'] ?></h3>
        <p>
          <?php
          if ($record['friends'] == null) {
            echo 'Heeft geen vrienden';
          } else {
            echo "Totale vrienden; {$record['friends']}";
          }
          ?>
        </p>
        <div>
          <!-- <span class="iconSmall">
            <img src="../src/img/Logo.png" alt="">
          </span>
          <span class="iconSmall">
            <img src="../src/img/Logo.png" alt="">
          </span>
          <span class="iconSmall">
            <img src="../src/img/Logo.png" alt="">
          </span>
          <p>+5</p> -->
          <?php
          if ($record['friends'] == null) {
            echo '';
          }
          ?>
        </div>
      </div>
      <div class="profiel-buttons">
        <button class="active">
          Followed
        </button>
        <button class="border">
          Respect
        </button>
      </div>
    </div>
    <hr>
    <div class="nav">
      <ul>
        <li><a href="#1">Berichten</a></li>
        <li><a href="#2">Bio</a></li>
        <li><a href="#3">Vrienden</a></li>
        <li><a href="#4">Foto's</a></li>
      </ul>
      <button>
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M14 18C12.9 18 12 18.9 12 20C12 21.1 12.9 22 14 22C15.1 22 16 21.1 16 20C16 18.9 15.1 18 14 18ZM26 18C24.9 18 24 18.9 24 20C24 21.1 24.9 22 26 22C27.1 22 28 21.1 28 20C28 18.9 27.1 18 26 18ZM20 18C18.9 18 18 18.9 18 20C18 21.1 18.9 22 20 22C21.1 22 22 21.1 22 20C22 18.9 21.1 18 20 18Z" fill="white" />
        </svg>
      </button>
    </div>
  </div>
  <div class="content">
    <div>
      <p id="1">f</p>
    </div>
    <div>
      <p id="2">f</p>
    </div>
    <div>
      <p id="3">f</p>
    </div>
    <div>
      <p id="4">f</p>
    </div>
  </div>
</main>