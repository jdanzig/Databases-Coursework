<?php
// Determine if user exists
// Parameter: Username
function logged_in() 
{
  return isset($_SESSION['username']);
}

// Helper function: Does user exist?
function user_exists($username)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT COUNT(*) FROM Users u WHERE u.username = ?')
    or die('invalid query');
  $stmt->bind_param('s', $username);
  $stmt->execute() or die('query failed');
  $stmt->bind_result($usercount);
  $stmt->fetch();
  
  return ($usercount == 1);
}

// Helper function: Does tweet exist?
function tweet_exists($tweet_id)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT COUNT(*) FROM Tweets t WHERE t.tweet_id = ?')
    or die('invalid query');
  $stmt->bind_param('i', $tweet_id);
  $stmt->execute() or die('query failed');
  $stmt->bind_result($tweetcount);
  $stmt->fetch();
  
  return ($tweetcount == 1);
}

function login($username)
{
  if(logged_in()) return false;
  if(user_exists($username))
  {
    session_start();
    $_SESSION['username'] = $username;
    return true;
  } else {
    return false;
  }
}

function logout() 
{
  session_start();
  unset($_SESSION['username']);
  session_write_close();
}

// Table List: List of tables and number of entries in the table
// Parameters: None
function table_list()
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = "jdanzigDB"')
    or die('invalid query');
  $stmt->execute() or die('query failed');
  $tablelist = array();
  $stmt->bind_result($table_name, $table_rows);
  while($stmt->fetch())
  {
    $tablelist[] = array('table_name' => $table_name, 'table_rows' => $table_rows);
  }
  return $tablelist;
}

// Personal Timeline: Most recent tweets and retweets by a user in descending order by date, limit 20
// Parameter 1: User. Parameter 2: Offset. Parameter 3: Count
function personal_timeline($username, $skip = 0, $count = 20)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT DISTINCT t.tweet_id, t.creator_username, t.tweet_timestamp, t.tweet_body_text, t.image_url FROM Tweets AS t, Retweet AS r WHERE t.creator_username = ? OR (t.tweet_id = r.tweet_id AND r.username = ?) ORDER BY IFNULL(r.rt_timestamp, t.tweet_timestamp) DESC LIMIT ? OFFSET ?')
    or die('invalid query');
  $stmt->bind_param('ssii', $username, $username, $count, $skip);
  $stmt->execute() or die('query failed');
  $tweets = array();
  $stmt->bind_result($tweet_id, $creator_username, $tweet_timestamp, $tweet_body_text, $image_url);
  while($stmt->fetch())
  {
    $tweets[] = array('tweet_id' => $tweet_id, 'creator_username' => $creator_username, 'tweet_timestamp' => $tweet_timestamp, 'tweet_body_text' => $tweet_body_text, 'image_url' => $image_url);
  }
  $stmt->close();
  return $tweets;
}

// Twitter Timeline: Most recent tweets of users followed by current user in descending order by date, limit 20
// Parameter 1: User. Parameter 2: Offset. Parameter 3: Count
function twitter_timeline($username, $skip = 0, $count = 20)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT t.tweet_id, t.creator_username, t.tweet_timestamp, t.tweet_body_text, t.image_url FROM Tweets AS t, Follow AS f WHERE t.creator_username = f.followee_username AND f.follower_username = ? ORDER BY tweet_timestamp DESC LIMIT ? OFFSET ?')
    or die('invalid query');
  //$stmt->bind_param('s', $username);
  $stmt->bind_param('sii', $username, $count, $skip);
  $stmt->execute() or die('query failed');
  $tweets = array();
  $stmt->bind_result($tweet_id, $creator_username, $tweet_timestamp, $tweet_body_text, $image_url);
  while($stmt->fetch())
  {
    $tweets[] = array('tweet_id' => $tweet_id, 'creator_username' => $creator_username, 'tweet_timestamp' => $tweet_timestamp, 'tweet_body_text' => $tweet_body_text, 'image_url' => $image_url);
  }
  $stmt->close();
  return $tweets;
}

// Mailbox: All PMs sent or received by a given user, ordered by descending pm_timestamp
// Parameter 1: User. Parameter 2: Offset. Parameter 3: Count
function mailbox($username, $skip = 0, $count = 20)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT pm_id, sender_username, receiver_username, pm_body_text, pm_timestamp FROM PMs WHERE sender_username = ? OR receiver_username = ? ORDER BY pm_timestamp DESC LIMIT ? OFFSET ?')
    or die('invalid query');
  $stmt->bind_param('ssii', $username, $username, $count, $skip);
  $stmt->execute() or die('query failed');
  $pms = array();
  $stmt->bind_result($pm_id, $sender_username, $receiver_username, $pm_body_text, $pm_timestamp);
  while($stmt->fetch())
  {
    $pms[] = array('pm_id' => $pm_id, 'sender_username' => $sender_username, 'receiver_username' => $receiver_username, 'pm_body_text' => $pm_body_text, 'pm_timestamp' => $pm_timestamp);
  }
  $stmt->close();
  return $pms;
}

// Followee Count: Number of users followed by a user
// Parameter 1: User.
function followee_count($username)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT COUNT(*) FROM Follow WHERE follower_username = ?')
    or die('invalid query');
  $stmt->bind_param('s', $username);
  $stmt->execute() or die('query failed');
  $stmt->bind_result($followeecount);
  $stmt->fetch();
  $stmt->close();
  return $followeecount;
}

// Random Users: Print 5 random usernames
// Parameters: Count (default 5)
function random_usernames($count = 5)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT username FROM Users ORDER BY RAND() LIMIT ?')
    or die('invalid query');
  $stmt->bind_param('i', $count);
  $stmt->execute() or die('query failed');
  $usernames = array();
  $stmt->bind_result($username);
  while($stmt->fetch())
  {
    $usernames[] = $username;
  }
  return $usernames;
}

// Mutual Followers: List all pairs of users who follow each other
// Parameters: None
function mutual_followers()
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT A.follower_username, A.followee_username FROM Follow A, Follow B WHERE A.followee_username = B.follower_username AND A.follower_username = B.followee_username')
    or die('invalid query');
  $stmt->execute() or die('query failed');
  $userpairs = array();
  $stmt->bind_result($user_a, $user_b);
  while($stmt->fetch())
  {
    $userpairs[] = array($user_a, $user_b);
  }
  return $userpairs;
}

// Show Followees: List all users followed by a given user
// Parameters 1: User
function show_followees($username)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT U.username, U.name, U.description FROM Users U, Follow F WHERE F.followee_username = U.username AND F.follower_username = ?')
    or die('invalid query');
  $stmt->bind_param('s', $username);
  $stmt->execute() or die('query failed');
  $users = array();
  $stmt->bind_result($username, $real_name, $description);
  while($stmt->fetch())
  {
    $users[] = array('username' => $username, 'name' => $real_name, 'description' => $description);
  }
  return $users;
}

// Show Followers: List all users following a given user
// Parameter 1: User
function show_followers($username)
{
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('SELECT U.username, U.name, U.description FROM Users U, Follow F WHERE F.follower_username = U.username AND F.followee_username = ?')
    or die('invalid query');
  $stmt->bind_param('s', $username);
  $stmt->execute() or die('query failed');
  $users = array();
  $stmt->bind_result($username, $real_name, $description);
  while($stmt->fetch())
  {
    $users[] = array('username' => $username, 'name' => $real_name, 'description' => $description);
  }
  return $users;
}

// ** DATA ENTRY FUNCTIONS **

// Tweet: Write a Tweet!
function tweet($body_text)
{  
  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('INSERT INTO Tweets (creator_username, tweet_timestamp, tweet_body_text) VALUES (?, NOW(), ?)')
    or die('invalid query');
  $stmt->bind_param('ss', $_SESSION['username'], $body_text);
  $stmt->execute() or die('query failed');
  return true;
}

// Follow: Add entry to Follow 
function follow($username)
{
  if(!user_exists($username)) return false;

  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('INSERT INTO Follow (follower_username, followee_username) VALUES (?, ?)')
    or die('invalid query');
  $stmt->bind_param('ss', $_SESSION['username'], $username);
  $stmt->execute() or die('query failed');
  return true;
}

// Unfollow: Remove entry from Follow
function unfollow($username)
{
  if(!user_exists($username)) return false;

  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('DELETE FROM Follow WHERE follower_username = ? AND followee_username = ?')
    or die('invalid query');
  $stmt->bind_param('ss', $_SESSION['username'], $username);
  $stmt->execute() or die('query failed');
  return true;
}

// go_away: Stop a user from following current user
function go_away($username)
{
  if(!user_exists($username)) return false;

  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('DELETE FROM Follow WHERE follower_username = ? AND followee_username = ?')
    or die('invalid query');
  $stmt->bind_param('ss', $username, $_SESSION['username']);
  $stmt->execute() or die('query failed');
  return true;
}

// Retweet: Add entry to Retweet
function retweet($tweet_id)
{
  if(!tweet_exists($tweet_id)) return false;

  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('INSERT IGNORE INTO Retweet (username, tweet_id, rt_timestamp) VALUES (?, ?, NOW())')
    or die('invalid query');
  $stmt->bind_param('si', $_SESSION['username'], $tweet_id);
  $stmt->execute() or die('query failed');
  return true;
}

// Send direct message
function send_dm($username, $body_text)
{
  if(!user_exists($username)) return false;

  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('INSERT INTO PMs (sender_username, receiver_username, pm_body_text, pm_timestamp) VALUES (?, ?, ?, NOW())')
    or die('invalid query');
  $stmt->bind_param('sss', $_SESSION['username'], $username, $body_text);
  $stmt->execute() or die('query failed');
  return true;
}

// Create user
function create_user($username, $name, $desc)
{
  if(logged_in()) return false;
  if(user_exists($username)) return false;

  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt-> prepare('INSERT INTO Users (username, name, description) VALUES (?, ?, ?)')
    or die('invalid query');
  $stmt->bind_param('sss', $username, $name, $desc);
  $stmt->execute() or die('query failed');

  login($username);
  return true;
}

// Delete Tweet
function delete_tweet($tweet_id)
{
  if(!tweet_exists($tweet_id)) return false;

  global $dbcon;
  $stmt = $dbcon->stmt_init();
  $stmt->prepare('DELETE FROM Tweets WHERE tweet_id = ? AND creator_username = ?')
    or die('invalid query');
  $stmt->bind_param('is', $tweet_id, $_SESSION['username']);
  $stmt->execute() or die('query failed');
}

?>
