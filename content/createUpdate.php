  <?php
  $sql = "SELECT nickname,avatar FROM users WHERE usersId = " . $_SESSION['id'];
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    // error page if user does not exist
  }
  $record = mysqli_fetch_assoc($result);
  ?>
  <form class="side-main-content post full-height" data-post-id="1">
    <header>
      <img class="icon-rounded medium" src="<?php echo $record['avatar'] ?>" alt="profile img">
      <div>
        <h4>
          <?php echo $record['nickname'] ?>
        </h4>
        <h5>
          <?php echo date('d-m-Y H:i:s') ?>
        </h5>
      </div>
    </header>
    <main class="full-height">
      <textarea name="message" form="form" placeholder="What's on your mind?"></textarea>
      <input class="mt-auto" type="file" id="krabbel" name="krabbel" accept="image/*">
      <button type="submit">
        post
      </button>
    </main>
  </form>