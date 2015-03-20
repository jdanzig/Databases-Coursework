<?php
require 'includes/header.inc.php';
?>
<div class="page-header">
  <h1>jdanzig Twitter</h1>
</div>
<ul class="nav nav-pills nav-stacked">
<?php if(!logged_in()) { ?>
  <li role="presentation">
    <a href="login.php">Login<span class="query">SELECT COUNT(*) FROM Users u WHERE u.username = ?</span></a>
  </li>
  <li role="presentation">
    <a href="signup.php">New user? Sign up!<span class="query">INSERT INTO Users (username, name, description) VALUES (?, ?, ?)</span></a>
  </li>
<?php } else { ?>
  <li role="presentation">
    <a href="personal_timeline.php">My Tweets<span class="query">INSERT INTO Tweets (creator_username, tweet_timestamp, tweet_body_text) VALUES (?, NOW(), ?)')</span><span class="query">DELETE FROM Tweets WHERE tweet_id = ? AND creator_username = ?</span></a>
  </li>
  <li role="presentation">
    <a href="timeline.php">My Twitter Feed<span class="query">INSERT IGNORE INTO Retweet (username, tweet_id, rt_timestamp) VALUES (?, ?, NOW())</span></a>
  </li>
  <li role="presentation">
    <a href="mailbox.php">Private Message Mailbox<span class="query">INSERT INTO PMs (sender_username, receiver_username, pm_body_text, pm_timestamp) VALUES (?, ?, ?, NOW())</span></a>
  </li>
  <li role="presentation">
    <a href="followers.php">Users Following Me<span class="query">DELETE FROM Follow WHERE follower_username = ? AND followee_username = ?</span></a>
  </li>
  <li role="presentation">
    <a href="followees.php">Users I Follow<span class="query">INSERT INTO Follow (follower_username, followee_username) VALUES (?, ?)</span><span class="query">DELETE FROM Follow WHERE follower_username = ? AND followee_username = ?</span></a>
  </li>
  <li role="presentation">
    <a href="logout.php">Logout</a>
  </li>
<?php } ?>
</ul>
<?php
require 'includes/footer.inc.php';
?>
