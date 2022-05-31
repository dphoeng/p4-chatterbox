<?php

if (!isset($_SESSION["id"])) {
  header("Location: ../index.php");
  exit();
}

$id = explode("/", $_GET['content']);

// if $id[1] does not exist or is empty, redirect to logged in user's profile
if (count($id) < 2) {
  header("Location: ../index.php?content=profiel/2");
  exit();
} else if ($id[1] == "") {
  header("Location: ../index.php?content=profiel/2");
  exit();
}

$sql = "SELECT usersId, nickname, birthday,respect,bio,avatar,friends,background FROM users WHERE usersId = {$id[1]}";
$result = mysqli_query($conn, $sql);
if (!$result) {
  // error page if user does not exist
}
$record = mysqli_fetch_assoc($result);
// var_dump($record);

?>

<main class="profiel-page profiel-background side-main-content" )>
  <style>
    .main-content {
      background: url(".<?php echo $record['background'] ?>");
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
  <div class="profiel">
    <div class="info">
      <div class="iconLarge profiel-icon">
        <img src=".<?php echo $record['avatar'] ?>" alt="">
      </div>
      <div class="profiel-naam">
        <h3><?php echo $record['nickname'] ?></h3>
        <p>
          <?php
          if ($record['friends'] == null) {
            echo 'Heeft geen vrienden';
          } else {
            $friend_count = json_decode($record['friends'])->friend_count;
            if ($friend_count < 1)
              echo 'Heeft geen vrienden';
            else
              echo "Totale vrienden: {$friend_count}";
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
        <a href="./includes/addfriend.inc.php?profile=<?= $id[1] ?>"><button class="active">
            Followed
          </button></a>
        <a href="./includes/giverespect.inc.php?profile=<?= $id[1] ?>"><button class="border">
            Respect
          </button></a>
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
      <?php
      if ($_SESSION['id'] == $url[1]) {
        echo '<a href="?content=profielEdit"><svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.5744 13.3759L26.6185 15.4201L6.48799 35.5506H4.44383V33.5065L24.5744 13.3759ZM32.5733 0C32.0178 0 31.4401 0.222191 31.0179 0.644355L26.9518 4.71046L35.284 13.0426L39.3501 8.97653C40.2166 8.10999 40.2166 6.71018 39.3501 5.84363L34.1508 0.644355C33.7064 0.199972 33.151 0 32.5733 0ZM24.5744 7.08791L0 31.6623V39.9944H8.33218L32.9065 15.4201L24.5744 7.08791Z" fill="var(--text-2)" /></svg></a>';
      }
      ?>
    </div>
  </div>
  <div class="side-scroll-container">
    <div class="side-scroll-item">
      <p id="1">f</p>
    </div>
    <div class="side-scroll-item">
      <p id="2">f</p>
    </div>
    <div class="side-scroll-item">
      <p id="3">f</p>
    </div>
    <div class="side-scroll-item">
      <p id="4">f</p>
    </div>
  </div>
</main>