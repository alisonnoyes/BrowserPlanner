<?php
  include "sqlsetup.php";

  $htmldata = "";
  $projectdata = mysqli_query($conn, 'SELECT * FROM ' . $_POST["tablename"]);
  while ($row = mysqli_fetch_array($projectdata)) {
    if ($_POST["tablename"] == "projects") {
      $htmldata .= "<tr><td>" . $row["title"] . "</td><td>" . $row["count"] . "</td></tr>";
    }
    else if ($_POST["tablename"] == "tasks_and_events") {
      $htmldata .= "<tr><td>" . $row["title"] . "</td>";
      $htmldata .= "<td>" . $row["due_date"] . "</td>";
      $htmldata .= "<td>" . $row["scheduled_date"] . "</td>";
      $htmldata .= "<td>" . $row["priority"] . "</td>";
      $htmldata .= "<td>" . $row["project"] . "</td>";
      $htmldata .= "<td>" . $row["parent"] . "</td>";
      $htmldata .= "<td>" . $row["done"] . "</td>";
      $htmldata .= "<td><span class='delete' id='del_" . $row["title"] . "'>Delete</span></td></tr>";
    }
  }

  echo $htmldata;
  exit;
?>
