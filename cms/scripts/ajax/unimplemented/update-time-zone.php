<?php
if(!isset($_SESSION)) { session_start(); }

if(isset($_GET['timezone'])) {
  $_SESSION['timezone'] = $_GET['timezone'];
  echo $_SESSION['timezone'];
}

?>
