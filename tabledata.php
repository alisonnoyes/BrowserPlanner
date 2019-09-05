<?php
  include "sqlsetup.php";

  $htmldata = "";
  $projectdata = mysqli_query($conn, 'SELECT * FROM ' . $_POST["tablename"]);
  while ($row = mysqli_fetch_array($projectdata)) {
    $htmldata .= "<tr><td>" . $row["title"] . "</td><td>" . $row["count"] . "</td></tr>";
  }

  echo $htmldata;
  exit;
?>
