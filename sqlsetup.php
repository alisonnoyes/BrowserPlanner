<?php
  // Connecting to the MySQL database
  $dbhost = 'localhost:3306';
  $dbuser = 'alison';
  $dbpass = 'alisonmei';
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass);

  if(! $conn ) {
    die('Could not connect: ' . mysqli_connect_error());
  }
  // echo 'Connected successfully';

  mysqli_select_db($conn, 'planner');
?>
