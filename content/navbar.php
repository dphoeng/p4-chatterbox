<?php

$sql = "SELECT nickname, avatar, friends FROM users WHERE usersId = {$_SESSION['id']}";
$result = mysqli_query($conn, $sql);
if (!$result) {
  // error page if user does not exist
}
$record = mysqli_fetch_assoc($result);

$rows = "";

if (!$record['friends']) {
  $rows .= "<li><h4>No notifications</h4></li>";
} else {
  $decoded = json_decode($record['friends']);

  $ret = isset($_GET['content']) ? $_GET['content'] : "home";

  foreach ($decoded->friends as $friend) {
    if ($friend->request_type == "requested") {
      $friendId = intval($friend->id);
      $friendSql = "SELECT * FROM users WHERE usersId = {$friendId}";
      $friendResult = mysqli_query($conn, $friendSql);
      if (mysqli_num_rows($friendResult) > 0) {
        $friendReturn = $friendResult->fetch_assoc();
        $rows .= "<li>
                    <img class='icon-rounded medium' src='{$friendReturn['avatar']}' alt='profile image'>
                    <h4>{$friendReturn['nickname']}</h4>
                    <a href='./includes/acceptfriend.inc.php?friend={$friendReturn['usersId']}&ret={$ret}'><button class='button green'>
                      Accept
                    </button></a>
                    <a href='./includes/rejectfriend.inc.php?friend={$friendReturn['usersId']}&ret={$ret}'><button class='button green'>
                      Decline
                    </button></a>
                  </li>";
      } else {
        // user deleted
      }
    }
  }
}

if (strlen($rows) < 1) {
  $rows .= "<li><h4>No notifications</h4></li>";
}

?>

<nav class="main-nav">
  <ul class="side-nav-left">
    <li>
      <a href="." class="icon-rounded">
        <img class="icon-rounded medium" src="../src/img/Logo.png" alt="">
      </a>
    </li>
    <!-- <li>
      <button data-open-dropdown="search" class="icon-rounded button medium">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12.8645 11.3208H12.0515L11.7633 11.0429C12.7719 9.86964 13.3791 8.34648 13.3791 6.68954C13.3791 2.99485 10.3842 0 6.68954 0C2.99485 0 0 2.99485 0 6.68954C0 10.3842 2.99485 13.3791 6.68954 13.3791C8.34648 13.3791 9.86964 12.7719 11.0429 11.7633L11.3208 12.0515V12.8645L16.4666 18L18 16.4666L12.8645 11.3208ZM6.68954 11.3208C4.12693 11.3208 2.05832 9.25214 2.05832 6.68954C2.05832 4.12693 4.12693 2.05832 6.68954 2.05832C9.25214 2.05832 11.3208 4.12693 11.3208 6.68954C11.3208 9.25214 9.25214 11.3208 6.68954 11.3208Z" fill="#ACACAC" />
        </svg>
      </button>
    </li> -->
    <li>
      <button class="icon-rounded">
        <img class="icon-rounded medium" src="<?php echo $record['avatar'] ?>" alt="">
      </button>
    </li>
    <!-- <li>
      <input type="text" placeholder="Zoek voor vrienden">
    </li> -->
    <!-- <li class="dropdown-container search-dropdown">
      <ul class="side-nav-dropdown left">
        <div class="search-results">
        </div>
      </ul>
    </li> -->
  </ul>
  <ul class="side-nav-main">
    <li>
      <a href="?content=home" class="hover rounded">
        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1.25 24.75V12.9689L13 1.72976L24.75 12.9689V24.75H17.7955V18.2411C17.7955 15.5926 15.6485 13.4457 13 13.4457C10.3515 13.4457 8.20455 15.5927 8.20455 18.2411V24.75H1.25Z" stroke="#ACACAC" stroke-width="2.5" />
        </svg>
      </a>
    </li>
    <li>
      <a href="?content=vrienden" class="hover rounded">
        <svg width="38" height="26" viewBox="0 0 38 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3.24784 18.5426L3.2485 18.5423C5.24801 17.6658 7.02549 17.0463 8.5854 16.673C10.1397 16.3011 11.7432 16.1143 13.3977 16.1143C15.0523 16.1143 16.6482 16.3011 18.1873 16.6726C19.7337 17.0458 21.5047 17.6654 23.5051 18.5423L23.5058 18.5426C24.2251 18.8573 24.7754 19.3236 25.1822 19.9503C25.59 20.5786 25.7955 21.2746 25.7955 22.0644V25H1V22.0644C1 21.2689 1.20163 20.5691 1.59911 19.9398C1.99079 19.3196 2.53071 18.8563 3.24784 18.5426ZM29.6474 16.9418C31.2961 17.3607 32.5908 17.8294 33.556 18.335C34.3543 18.7974 34.956 19.3512 35.3883 19.9882C35.8022 20.5982 36.0113 21.2804 36.0113 22.0644V25H30.3076V22.0644C30.3076 20.132 29.8138 18.4544 28.7524 17.1111C28.6247 16.9494 28.492 16.792 28.3544 16.6387C28.793 16.7332 29.224 16.8342 29.6474 16.9418ZM13.3977 11.5604C11.7885 11.5604 10.5462 11.0579 9.58311 10.0948C8.62001 9.13173 8.11755 7.88947 8.11755 6.28019C8.11755 4.67092 8.62001 3.42866 9.58311 2.46556C10.5462 1.50246 11.7885 1 13.3977 1C15.007 1 16.2493 1.50246 17.2124 2.46556C18.1755 3.42866 18.6779 4.67092 18.6779 6.28019C18.6779 7.88947 18.1755 9.13173 17.2124 10.0948C16.2493 11.0579 15.007 11.5604 13.3977 11.5604ZM27.4702 6.28019C27.4702 7.88947 26.9677 9.13173 26.0046 10.0948C25.0415 11.0579 23.7993 11.5604 22.19 11.5604C22.1032 11.5604 22.0119 11.5584 21.9159 11.5544C22.193 11.0786 22.4248 10.5651 22.6127 10.0164C23.0027 8.87763 23.19 7.62852 23.19 6.28019C23.19 4.93338 23.0031 3.70478 22.6069 2.61104C22.4052 2.05437 22.1554 1.52124 21.8584 1.01206C21.9767 1.00391 22.0871 1 22.19 1C23.7993 1 25.0415 1.50246 26.0046 2.46556C26.9677 3.42866 27.4702 4.67092 27.4702 6.28019Z" stroke="#ACACAC" stroke-width="2" />
        </svg>
      </a>
    </li>
    <li>
      <a href="?content=focused" class="hover rounded">
        <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M16.5547 17.8321L17.8028 17L16.5547 16.1679L13.5547 14.1679L12 13.1315V15V19V20.8685L13.5547 19.8321L16.5547 17.8321ZM1.64875 24.5298L0.941646 25.2369L1.64875 24.5298C1.32501 24.2061 1.17725 23.8474 1.17725 23.4V10.4C1.17725 9.95258 1.32501 9.59395 1.64875 9.27021C1.97127 8.94769 2.32932 8.8 2.77725 8.8H23.5772C24.0249 8.8 24.3834 8.94751 24.7068 9.27002C25.0296 9.59352 25.1772 9.95222 25.1772 10.4V23.4C25.1772 23.8477 25.0297 24.2063 24.707 24.5298C24.3836 24.8524 24.0249 25 23.5772 25H2.77725C2.32932 25 1.97127 24.8523 1.64875 24.5298ZM3.77725 5.5V4.9H22.5772V5.5H3.77725ZM7.67725 1.6V1H18.6772V1.6H7.67725Z" stroke="#444444" stroke-width="2" />
        </svg>
      </a>
    </li>
    <li>
      <a href="?content=chat" class="hover rounded">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M7.05 20.95V19.65H23.8H25.25V18.2V7.05H26.55V24.4994L23.4253 21.3747L23.0006 20.95H22.4H7.05ZM1.45 17.4994V1.45H19.55V13.95H5.6H4.99939L4.5747 14.3747L1.45 17.4994Z" stroke="black" stroke-width="2.9" />
        </svg>
      </a>
    </li>
    <li>
      <button class="hover rounded solid">
        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M25.5 23.4412V25.5H0.5V23.4412H25.5ZM25.5 11.3809V13.4397H0.5V11.3809H25.5ZM25.5 2.55882H0.5V0.5H25.5V2.55882Z" stroke="black" />
        </svg>
      </button>
    </li>
  </ul>
  <ul class="side-nav-right">
    <li>
      <a href="?content=profiel/<?= $_SESSION['id']; ?>">
        <img class="icon-rounded small" src="<?php echo $record['avatar'] ?>" alt="">
        <p><?php echo $record['nickname'] ?></p>
      </a>
    </li>
    <li>
      <a href="?content=createUpdate&profiel=<?= $_SESSION['id']; ?>" data-open-popup="create-post" class="icon-rounded button medium">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9.9 18H8.1V9.9H0V8.1H8.1V0H9.9V8.1H18V9.9H9.9V18Z" fill="#F0F0F0" />
        </svg>
      </a>
    </li>
    <li class="dropdown-container" id="notifications">
      <button data-open-dropdown="notifications" class="icon-rounded button medium">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9 17.6028C9.99298 17.6028 10.8054 16.7904 10.8054 15.7974H7.19458C7.19458 16.7904 8.00702 17.6028 9 17.6028ZM14.4163 12.1866V7.67302C14.4163 4.90171 12.9448 2.58175 10.3541 1.9679V1.35406C10.3541 0.604814 9.74925 0 9 0C8.25075 0 7.64594 0.604814 7.64594 1.35406V1.9679C5.06419 2.58175 3.58375 4.89268 3.58375 7.67302V12.1866L1.77834 13.992V14.8947H16.2217V13.992L14.4163 12.1866ZM12.6108 13.0893H5.38917V7.67302C5.38917 5.4343 6.75226 3.61083 9 3.61083C11.2477 3.61083 12.6108 5.4343 12.6108 7.67302V13.0893ZM5.01003 1.42628L3.71916 0.135406C1.55266 1.78736 0.126379 4.333 0 7.22167H1.80542C1.94082 4.82949 3.16851 2.73521 5.01003 1.42628ZM16.1946 7.22167H18C17.8646 4.333 16.4383 1.78736 14.2808 0.135406L12.999 1.42628C14.8225 2.73521 16.0592 4.82949 16.1946 7.22167Z" fill="#F0F0F0" />
        </svg>
      </button>
      <ul class="side-nav-dropdown right below-nav">
        <?= $rows ?>
      </ul>
    </li>
    <li class="dropdown-container" id="more-options">
      <button data-open-dropdown="more-options" class="icon-rounded button medium">
        <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 17H18V15H0V17ZM0 9.11444H18V7.11444H0V9.11444ZM0 0V2H18V0H0Z" fill="#F0F0F0" />
        </svg>
      </button>
      <ul class="side-nav-dropdown right below-nav">
        <li>
          <span>
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.9 18H8.1V9.9H0V8.1H8.1V0H9.9V8.1H18V9.9H9.9V18ZM9.9 0H8.1V8.1H0V9.9H8.1V18H9.9V0Z" fill="#F0F0F0" />
            </svg>
          </span>
          <h4>settings</h4>
          <span>
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.9 18H8.1V9.9H0V8.1H8.1V0H9.9V8.1H18V9.9H9.9V18ZM9.9 0H8.1V8.1H0V9.9H8.1V18H9.9V0Z" fill="#F0F0F0" />
            </svg>
          </span>
        </li>
      </ul>
    </li>
  </ul>
</nav>