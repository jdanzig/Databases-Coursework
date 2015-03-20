<?php
require 'includes/header.inc.php';
if(logged_in() && isset($_POST['submit'])) {
  send_dm($_POST['username'], $_POST['body']);
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
$pms = mailbox($username, $skip, $count);
?>
<div class="page-header">
  <h1>Private Message Mailbox for  <?= htmlspecialchars($username); ?></h1>
</div>

<div class="col-sm-8">
  <div class="bs-callout bs-callout-info">
    <h4>Send New Private Message</h4>
    <form method="post" action="mailbox.php">
      <div><strong>Recipient Username: </strong><input type="text" name="username"></div><br>
      <div><textarea name="body" rows="4" cols="60"></textarea></div>
      <div style="text-align: right;"><input type="submit" name="submit" value="Send"></div>
    </form>
  </div>
  <ul class="list-group">
    <?php foreach( $pms as $pm) { ?>
    <li class="list-group-item">
      Message ID <?= $pm['pm_id'] ?>: 
      <?= htmlspecialchars($pm['pm_body_text']) ?><br/>
      <em>
        &mdash; from <?= htmlspecialchars($pm['sender_username']) ?> 
        to <?= htmlspecialchars($pm['receiver_username']) ?> 
        on <?= $pm['pm_timestamp'] ?>
      </em>
    </li>
    <?php } ?>
  </ul>

  <div class="well">
    <p>Count: <strong><?= count($pms) ?></strong></p>
  </div>

</div>

<?php require 'includes/footer.inc.php'; ?>
