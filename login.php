<?php
require_once 'includes/db.inc.php';
require_once 'includes/functions.inc.php';
if(isset($_POST['submit']) && login($_POST['username']))
{
  header('Location: final.php');
  exit();
}
require 'includes/header.inc.php';
?>
<div class="page-header">
  <h1>jdanzig Twitter</h1>
</div>

<div class="row">
  <div class="col-sm-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        Sign in to user account
      </div>
      <div class="panel-body">
        <form method="post" action="login.php">
          <input type="text" name="username" length="30" />
          <input type="submit" name="submit" value="Login" />
        </form>
      </div>
    </div>
</div>

<?php
require 'includes/footer.inc.php';
?>
