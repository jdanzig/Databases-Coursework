<?php
require 'includes/header.inc.php';
if(logged_in() && isset($_GET['username'])) {
  go_away($_GET['username']);
}

if(logged_in()) {
  $username = $_SESSION['username'];
} else {
  $username = $_POST['username'];
}
$followers = show_followers($username);
?>
<div class="page-header">
  <h1>Users Who Follow <?= htmlspecialchars($username); ?></h1>
</div>

<script type="text/javascript">
  $(function() {
    remove_icon = $('<span class="remove"><img src="assets/images/icons/stop.png" class="remove" />Remove From My Followers</span>');
    $('ul.list-group > li').append(remove_icon);
    $('ul.list-group > li > span.remove').click(function() {
      username = $(this).closest('ul.list-group > li').first().data('username');
      document.location.href = document.location.pathname + "?username=" + encodeURI(username);
      return false;
    });
  });
</script>

<div class="col-sm-8">
  <ul class="list-group">
    <?php foreach($followers as $user) { ?>
    <li class="list-group-item" data-username="<?= htmlspecialchars($user['username']) ?>">
      <strong><?= htmlspecialchars($user['username']) ?></strong> 
      (<em><?= htmlspecialchars($user['name']) ?></em>) <br/>
      <?= htmlspecialchars($user['description']) ?>
    </li>
    <?php } ?>
  </ul>

  <div class="well">
    <p>Count: <strong><?= count($followers) ?></strong></p>
  </div>

</div>
<?php
require 'includes/footer.inc.php';
?>
