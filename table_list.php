<?php
require 'includes/header.inc.php';
$tables = table_list();
?>

<div class="page-header">
  <h1>List of Tables in jdanzigDB</h1>
</div>

<div class="col-sm-4">
  <ul class="list-group">
    <?php foreach($tables as $table) { ?>
    <li class="list-group-item">
      Table <?= htmlspecialchars($table['table_name']) ?> has <?= htmlspecialchars($table['table_rows']) ?> entries.
    </li>
    <?php } ?>

    <div class="well">
      <p>Count: <strong><?= count($tables) ?></strong></p>
    </div>

  </ul>
</div>

<?php require 'includes/footer.inc.php'; ?>
