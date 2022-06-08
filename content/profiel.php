<?php

if (!isset($_SESSION["id"])) {
  header("Location: ../index.php");
  exit();
}

$profile = explode("/", $_GET['content'])[1];
$id = $_SESSION["id"];

// if $profile does not exist or is empty, redirect to logged in user's profile
if (strlen($profile) < 1) {
  header("Location: ../index.php?content=profiel/{$_SESSION["id"]}");
} else if ($profile == "") {
  header("Location: ../index.php?content=profiel/{$_SESSION["id"]}");
}

$sql = "SELECT usersId, nickname, birthday,respect,bio,avatar,friends,background FROM users WHERE usersId = {$profile}";
$result = mysqli_query($conn, $sql);

// no user found with this id
if (mysqli_num_rows($result) < 1) {
  header("Location: ../index.php?content=profiel/{$_SESSION["id"]}");
}
$record = mysqli_fetch_assoc($result);
$decoded = json_decode($record['friends']);

$posts = new Krabbels();
// $posts->getPosts($profile);
?>

<main class="profiel-page profiel-background side-main-content" )>
  <style>
    .main-content {
      background: url(".<?= $record['background'] ?>");
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
  <div class="profiel">
    <div class="info">
      <div class="iconLarge profiel-icon">
        <img src="<?= $record['avatar'] ?>" alt="">
      </div>
      <div class="profiel-naam">
        <h3><?= $record['nickname'] ?></h3>
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
          echo ", respect: {$record['respect']}";
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
        <?php
        if ($id !== $profile) {
          $found = false;
          if ($decoded) {
            foreach ($decoded->friends as $friend) {
              if ($id === $friend->id) {
                switch ($friend->request_type) {
                  case "friends":
                    // remove friend?
                    echo "<a href='#'><button class='no-click'>
									  Friends
									  </button></a>";
                    break;

                  case "requested":
                    // remove request?
                    echo "<a><button class='no-click'>
 									  Requested
									  </button></a>";
                    break;

                  case "requester":
                    echo "<a href='./includes/acceptfriend.inc.php?friend=$profile'><button class='active'>
									  Accept
									</button></a>";
                    break;
                }
                $found = true;
              }
            }
          }
          if (!$found) {
            echo "<a href='./includes/addfriend.inc.php?profile=$profile'><button class='active'>
					  Request
						</button></a>";
          }
          echo "<a href='./includes/giverespect.inc.php?profile=$profile'><button class='border'>
				  Respect
				  </button></a>";
        } ?>
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
      if ($_SESSION['id'] == $profile) {
        echo '<a href="?content=profielEdit"><svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.5744 13.3759L26.6185 15.4201L6.48799 35.5506H4.44383V33.5065L24.5744 13.3759ZM32.5733 0C32.0178 0 31.4401 0.222191 31.0179 0.644355L26.9518 4.71046L35.284 13.0426L39.3501 8.97653C40.2166 8.10999 40.2166 6.71018 39.3501 5.84363L34.1508 0.644355C33.7064 0.199972 33.151 0 32.5733 0ZM24.5744 7.08791L0 31.6623V39.9944H8.33218L32.9065 15.4201L24.5744 7.08791Z" fill="var(--text-2)" /></svg></a>';
      }
      ?>
    </div>
  </div>
  <div class="side-scroll-container">
    <div id="1" class="side-scroll-item">
      <div class="posts">
        <div class="post">
          <h4>Leave krabbel&nbsp;</h4>
          <a href="?content=createUpdate&profiel=<?= $profile; ?>" data-open-popup="create-post" class="icon-rounded button medium">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.9 18H8.1V9.9H0V8.1H8.1V0H9.9V8.1H18V9.9H9.9V18Z" fill="#F0F0F0" />
            </svg>
          </a>
        </div>
        <?= $posts->getPosts($profile, false) ?>
      </div>
    </div>
    <div id="2" class="side-scroll-item">
      <div class="post">
        <p><?= $record['bio']; ?></p>
      </div>
    </div>
    <div id="3" class="side-scroll-item">
      <div class="post">
        <p>f</p>
      </div>
    </div>
    <div id="4" class="side-scroll-item">
      <div class="post">
        <p>f</p>
      </div>
    </div>
  </div>
</main>