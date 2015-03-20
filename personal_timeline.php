<?php
require 'includes/header.inc.php';
if(logged_in() && isset($_REQUEST['submit'])) {
  if($_POST['action'] == "create") {
    tweet($_POST['body']);
  } else {
    delete_tweet($_GET['id']);
  }
}
if(logged_in()) {
  $username = $_SESSION['username'];
  $skip = 0;
  $count = 30;
} else {
  $username = $_POST['username'];
  $skip = $_POST['skip'];
  $count = $_POST['count'];
}
$tweets = personal_timeline($username, $skip, $count);
?>
<div class="page-header">
  <h1>Tweets and Retweets by <?= htmlspecialchars($username) ?></h1>
</div>
<script type="text/javascript">
  $(function() {
    delete_icon = $('<img src="assets/images/icons/delete.png" class="delete" />');
    $('ul.list-group > li').append(delete_icon);
    $('ul.list-group > li > img.delete').click(function() {
      tweet_id = $(this).closest('ul.list-group > li').first().data('id');
      document.location.href = document.location.pathname + "?submit=1&id=" + tweet_id;
      return false;
    });
  });
</script>
<div class="col-sm-8">
  <div class="bs-callout bs-callout-info">
    <h4>Post New Tweet</h4>
    <form method="post" action="personal_timeline.php">
      <input type="hidden" name="action" value="create">
      <div><textarea name="body" rows="4" cols="60"></textarea></div>
      <div style="text-align: right;"><input type="submit" name="submit" value="Tweet"></div>
    </form>
  </div>
  <ul class="list-group">
    <?php foreach($tweets as $tweet) { ?>
    <li class="list-group-item" data-id="<?= $tweet['tweet_id'] ?>">
      TweetID <?= $tweet['tweet_id'] ?>: <?= htmlspecialchars($tweet['tweet_body_text']) ?><br />
      <?php if(!is_null($tweet['image_url'])) { ?>Image URL: <?= htmlspecialchars($tweet['image_url']) ?><br /><?php } ?>
      <em>&mdash; by <?= htmlspecialchars($tweet['creator_username']) ?> on <?= $tweet['tweet_timestamp'] ?></em>
    </li>
    <?php } ?>

    <div class="well">
      <p>Count: <strong><?= count($tweets) ?></strong></p>
    </div>

  </ul>
</div>

<?php require 'includes/footer.inc.php'; ?>
