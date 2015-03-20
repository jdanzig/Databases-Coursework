<?php
// Connection parameters 
$host = 'cspp53001.cs.uchicago.edu';
$username = 'jdanzig';
$password = 'jdanzigpassword';
$database = 'jdanzigDB';

// Attempting to connect 
$dbcon = new mysqli($host, $username, $password, $database)
   or die('Could not connect: ' . mysqli_connect_error());
function dbcon_close()
{
  global $dbcon;
  $dbcon->close();
  return true;
}
register_shutdown_function('dbcon_close');
session_start();
?>
