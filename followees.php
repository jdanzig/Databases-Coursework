<?php
require 'includes/header.inc.php';
if(logged_in() && isset($_GET['unfollow'])) {
  unfollow($_GET['unfollow']);
}
if(logged_in() && isset($_POST['submit'])) {
  follow($_POST['username']);
}

if(logged_in()) {
  $username = $_SESSION['username'];
} else {
  $username = $_POST['username'];
}
$followees = show_followees($username);
?>
<div class="page-header">
  <h1>Users Followed by <?= htmlspecialchars($username); ?></h1>
</div>

<script type="text/javascript">
  $(function() {
    unfollow_icon = $('<span class="unfollow"><img src="assets/images/icons/status_offline.png" class="unfollow" />Unfollow</span>');
    $('ul.list-group > li').append(unfollow_icon);
    $('ul.list-group > li > span.unfollow').click(function() {
      username = $(this).closest('ul.list-group > li').first().data('username');
      document.location.href = document.location.pathname + "?unfollow=" + encodeURI(username);
      return false;
    });
  });
</script>

<div class="col-sm-8">
  <div class="bs-callout bs-callout-info">
    <h4>Follow a User</h4>
    <form method="post" action="followees.php">
      <div><strong>Username: </strong><input type="text" name="username"></div><br>
      <div><input type="submit" name="submit" value="Follow"></div>
    </form>
  </div>

  <ul class="list-group">
    <?php foreach($followees as $user) { ?>
    <li class="list-group-item" data-username="<?= htmlspecialchars($user['username']) ?>">
      <strong><?= htmlspecialchars($user['username']) ?></strong> 
      (<em><?= htmlspecialchars($user['name']) ?></em>) <br/>
      <?= htmlspecialchars($user['description']) ?>
    </li>
    <?php } ?>
  </ul>

  <div class="well">
    <p>Count: <strong><?= count($followees) ?></strong></p>
  </div>

</div>
<?php
require 'includes/footer.inc.php';
?>
