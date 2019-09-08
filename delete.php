<?php
  include "sqlsetup.php";

  $id = $_POST["id"];

  if ($id != 0) {
    // Check that the row exists
    $check = mysqli_query($conn, "SELECT * FROM tasks_and_events WHERE id='" . $id . "'");
    $numrows = mysqli_num_rows($check);
    $numdel = 1;

    if ($numrows > 0) {
      // Delete the row
      $querystring = "DELETE FROM tasks_and_events WHERE id='" . $id . "'";
      mysqli_query($conn, $querystring);

      $children = mysqli_query($conn, "SELECT * FROM tasks_and_events WHERE parent='" . $id . "'");
      $numdel = 1 + mysqli_num_rows($children);
      mysqli_query($conn, "DELETE FROM tasks_and_events WHERE parent='" . $id . "'");
    }

    while ($row = mysqli_fetch_array($check)) {
      $prevcount = mysqli_query($conn, "SELECT count FROM projects WHERE title='" . $row['project'] . "'");
      $numprojrows = mysqli_num_rows($prevcount);
      if ($numprojrows > 0) {
        $count = array_values(mysqli_fetch_assoc($prevcount))[0];
        $projcountquery = "UPDATE projects SET count=" . ($count - $numdel) . " WHERE title='" . $row['project'] . "'";
        mysqli_query($conn, $projcountquery);
      }
    }

    echo 1;
    exit;
  }

  echo $id;
  exit;
?>
