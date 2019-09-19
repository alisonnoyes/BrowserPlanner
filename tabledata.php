<?php
  include "sqlsetup.php";

  $htmldata = "";
  $projectdata = "";

  if ($_POST["tablename"] == "projects") {
    $projectdata = mysqli_query($conn, 'SELECT * FROM ' . $_POST["tablename"]);
  }

  while ($row = mysqli_fetch_array($projectdata)) {
    if ($_POST["tablename"] == "projects") {
      $htmldata .= "<tr><td><span class='circle' style='background-color:" . $row["color"] . "'></span></td><td><a href=?" . $row["title"] . ">" . $row["title"] . "</a></td><td>" . $row["count"] . "</td></tr>";
    }
  }

  echo $htmldata;
  exit;
?>
