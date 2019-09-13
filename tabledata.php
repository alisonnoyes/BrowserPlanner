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
      $htmldata .= "<td>" . $row["scheduled_time"] . "</td>";
      $htmldata .= "<td>" . $row["priority"] . "</td>";
      $htmldata .= "<td>" . $row["project"] . "</td>";
      $htmldata .= "<td>" . $row["parent"] . "</td>";
      $htmldata .= "<td>" . $row["done"] . "</td>";
      $htmldata .= "<td>";
      $htmldata .= "<span class='delete' id='del_" . $row["id"] . "'>Delete</span><br>";
      $htmldata .= "<div id='addtime' style='display: block'><a href='javascript:swapDiv(\"addtime\", \"timeform\")'>Add time</a></div>";
      $htmldata .= "<div id='timeform' style='display: none'>";
      $htmldata .= "<form action='planner.php' method='POST'>";
      $htmldata .= "<font color='white' face='helvetica'>Time: </font> <input type='time' name='time'/>";
      $htmldata .= "<input type='number' name='id' style='display: none' value=" . $row["id"] . "></input>";
      $htmldata .= "<input type='submit' value='Submit' />";
      $htmldata .= "</form></div></td>";
    }
  }

  echo $htmldata;
  exit;
?>
