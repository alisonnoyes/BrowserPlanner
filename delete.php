<?php
  include "sqlsetup.php";

  $title = $_POST["title"];

  if ($title != "") {
    // Check that the row exists
    $check = mysqli_query($conn, "SELECT * FROM tasks_and_events WHERE title='" . $title . "'");
    $numrows = mysqli_num_rows($check);

    while ($row = mysqli_fetch_array($check)) {
      $prevcount = mysqli_query($conn, "SELECT count FROM projects WHERE title='" . $row['project'] . "'");
      $count = array_values(mysqli_fetch_assoc($prevcount))[0];
      $projcountquery = "UPDATE projects SET count=" . ($count - 1) . " WHERE title='" . $row['project'] . "'";
      mysqli_query($conn, $projcountquery);
    }

    if ($numrows > 0) {
      // Delete the row
      $querystring = "DELETE FROM tasks_and_events WHERE title='" . $title . "'";
      mysqli_query($conn, $querystring);
      echo 1;
      exit;
    }
  }

  echo $title;
  exit;
?>
