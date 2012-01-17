<?php
require_once '../config.php';
session_name('ft');
session_start();
session_destroy();
session_unset();
echo "You have been successfully logged out!";
 echo "<br/>Taking you back to the homepage...";
  echo "<script>window.location=\"".$base."/\"</script>";
?>