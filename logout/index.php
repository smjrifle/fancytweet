<?php
require_once '../config.php';
session_start();
session_unset();
session_destroy();
echo "You have been successfully logged out!";
 echo "<br/>Taking you back to the homepage...";
  echo "<script>window.location=\"".$base."/\"</script>";
?>