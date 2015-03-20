<?php
require 'includes/header.inc.php';
if(logged_in() && isset($_GET['id'])) {
  retweet($_GET['id']);
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
$tweets = twitter_timeline($username, $skip, $count);
?>
<div class="page-header">
  <h1>User Timeline for <?= htmlspecialchars($username) ?></h1>
</div>
<script type="text/javascript">
  $(function() {
    retweet_icon = $('<span class="retweet"><img src="assets/images/icons/arrow_rotate_anticlockwise.png" class="retweet" />Retweet</span>');
    $('ul.list-group > li').append(retweet_icon);
    $('ul.list-group > li > span.retweet').click(function() {
      tweet_id = $(this).closest('ul.list-group > li').first().data('id');
      document.location.href = document.location.pathname + "?id=" + tweet_id;
      return false;
    });
  });
</script>
<div class="col-sm-8">
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
