<?php
require_once 'includes/db.inc.php';
require_once 'includes/functions.inc.php';
if(isset($_POST['submit']))
{
  create_user($_POST['username'],$_POST['name'],$_POST['description']);
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
        Sign up for new user account
      </div>
      <div class="panel-body">
        <form method="post" action="signup.php">
          <div><strong>Unique Username: </strong><input type="text" name="username" length="30" /></div>
          <div>Display Name: <input type="text" name="name" length="30" /></div>
          <div>Description: <input type="text" name="description" length="30" /></div>
          <input type="submit" name="submit" value="Register" />
        </form>
      </div>
    </div>
</div>

<?php
require 'includes/footer.inc.php';
?>
