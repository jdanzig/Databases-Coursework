<?php
require 'includes/header.inc.php';
?>
<div class="page-header">
  <h1>jdanzig Twitter</h1>
</div>

<div class="row">
  <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        Database Tables 
        <a class="query">SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = "jdanzigDB"</a>
      </div>
      <div class="panel-body">
        <p><a href="table_list.php">List of Database Tables</a></p>
      </div>
    </div>
  </div>

  <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        Personal Tweets
        <a class="query">SELECT DISTINCT t.tweet_id, t.creator_username, t.tweet_timestamp, t.tweet_body_text, t.image_url FROM Tweets AS t, Retweet AS r WHERE t.creator_username = ? OR (t.tweet_id = r.tweet_id AND r.username = ?) ORDER BY IFNULL(r.rt_timestamp, t.tweet_timestamp) DESC LIMIT ? OFFSET ?</a>
      </div>
      <div class="panel-body">
        <form method="post" action="personal_timeline.php">
          <input type="hidden" name="skip" value="0" />
          <input type="hidden" name="count" value="20" />
          <input type="text" name="username" length="30" />
          <input type="submit" name="submit" value="Search" />
        </form>
      </div>
    </div>
  </div>

    <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        User Timeline
        <a class="query">
          SELECT t.tweet_id, t.creator_username, t.tweet_timestamp, t.tweet_body_text, t.image_url FROM Tweets AS t, Follow AS f WHERE t.creator_username = f.followee_username AND f.follower_username = ? ORDER BY tweet_timestamp DESC LIMIT ? OFFSET ?
        </a>
      </div>
      <div class="panel-body">
        <form method="post" action="timeline.php">
          <input type="hidden" name="skip" value="0" />
          <input type="hidden" name="count" value="20" />
          <input type="text" name="username" length="30" />
          <input type="submit" name="submit" value="Search" />
        </form>
      </div>
    </div>
  </div>

  <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        User Private Message Mailbox
        <a class="query">
          SELECT pm_id, sender_username, receiver_username, pm_body_text, pm_timestamp FROM PMs WHERE sender_username = ? OR receiver_username = ? ORDER BY pm_timestamp DESC LIMIT ? OFFSET ?
        </a>
      </div>
      <div class="panel-body">
        <form method="post" action="mailbox.php">
          <input type="hidden" name="skip" value="0" />
          <input type="hidden" name="count" value="20" />
          <input type="text" name="username" length="30" />
          <input type="submit" name="submit" value="Search" />
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        Mutual Followers
        <a class="query">
          SELECT A.follower_username, A.followee_username FROM Follow A, Follow B WHERE A.followee_username = B.follower_username AND A.follower_username = B.followee_username
        </a>
      </div>
      <div class="panel-body">
        <form method="post" action="mutual_followers.php">
          <input type="submit" name="submit" value="Lookup" />
        </form>
      </div>
    </div>
  </div>

  <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        Show Followers
        <a class="query">
          SELECT U.username, U.name, U.description FROM Users U, Follow F WHERE F.followee_username = U.username AND F.follower_username = ?
        </a>
      </div>
      <div class="panel-body">
        <form method="post" action="followers.php">
          <input type="text" name="username" length="30" />
          <input type="submit" name="submit" value="Search" />
        </form>
      </div>
    </div>
  </div>

  <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        Show Followees
        <a class="query">
          SELECT U.username, U.name, U.description FROM Users U, Follow F WHERE F.follower_username = U.username AND F.followee_username = ?
        </a>
      </div>
      <div class="panel-body">
        <form method="post" action="followees.php">
          <input type="text" name="username" length="30" />
          <input type="submit" name="submit" value="Search" />
        </form>
      </div>
    </div>
  </div>

  <div class="col-sm-3">
    <ul class="list-group">
      <li class="list-group-item active">Sample Usernames<a class="query">SELECT username FROM Users ORDER BY RAND() LIMIT ?</a></li>
      <?php foreach(random_usernames() as $username) { ?><li class="list-group-item"><?= htmlspecialchars($username) ?></li><?php } ?>
    </ul>
  </div>
</div>

<?php
require 'includes/footer.inc.php';
?>
