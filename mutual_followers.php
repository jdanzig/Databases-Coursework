<?php
require 'includes/header.inc.php';
$userpairs = mutual_followers();
?>

<div class="page-header">
  <h1>List of Users Who Follow Each Other</h1>
</div>

<div class="col-sm-4">
  <ul class="list-group">
    <?php foreach($userpairs as $userpair) { ?>
    <li class="list-group-item">
      User <strong><?= htmlspecialchars($userpair[0]) ?></strong> 
      follows and is followed by <strong><?= htmlspecialchars($userpair[1]) ?></strong>.
    </li>
    <?php } ?>
  </ul>

  <div class="well">
    <p>Distinct Pair Count: <strong><?= count($userpairs)/2 ?></strong></p>
  </div>

</div>

<?php require 'includes/footer.inc.php'; ?>
