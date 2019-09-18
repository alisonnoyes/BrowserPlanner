<?php
  include "sqlsetup.php";

  $htmlstr = "";

  function generateRow($row) {
    global $htmlstr;
    $htmlstr .= "<tr>";
    $htmlstr .= "<td>" . $row["title"] . "</td>";
    $htmlstr .= "<td>" . $row["due_date"] . "</td>";
    $htmlstr .= "<td>" . $row["scheduled_date"] . "</td>";
    $htmlstr .= "<td>" . $row["scheduled_time"] . "</td>";
    $htmlstr .= "<td>" . $row["priority"] . "</td>";
    $htmlstr .= "<td>" . $row["project"] . "</td>";
    $htmlstr .= "<td>" . $row["parent"] . "</td>";
    $htmlstr .= "<td>" . $row["done"] . "</td>";
    $htmlstr .= "<td>";
    $htmlstr .= "<span class='delete' id='del_" . $row["id"] . "'>Delete</span><br>";
    $htmlstr .= "<span class='done' id='done_" . $row["id"] . "'>Done</span><br>";
    $htmlstr .= "<div id='addtime' style='display: block'><a href='javascript:swapDiv(\"addtime\", \"timeform\")'>Add time</a></div>";
    $htmlstr .= "<div id='timeform' style='display: none'>";
    $htmlstr .= "<form action='planner.php' method='POST'>";
    $htmlstr .= "<font color='white' face='helvetica'>Time: </font> <input type='time' name='time'/>";
    $htmlstr .= "<input type='number' name='id' style='display: none' value=" . $row["id"] . "></input>";
    $htmlstr .= "<input type='submit' value='Submit' />";
    $htmlstr .= "</form></div></td>";
    $htmlstr .= "</tr>";
  }

  // Tasks and events table
  $htmlstr .= "<table class='datatable' id='taskeventtable'>";

  $data = "";
  $url = $_POST["url"];
  $view = substr($url, strpos($url, "?") + 1);
  if ($view == "lanner.php" || $view == "") {
    $htmlstr .= "<h2>All Tasks and Events</h2>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) ORDER BY DATE(scheduled_date) ASC, scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateRow($rowdata);
    }
  }
  else if ($view == "today") {
    $htmlstr .= "<h2>Today</h2>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND scheduled_date = CURDATE() ORDER BY scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateRow($rowdata);
    }
  }
  else if ($view == "week") {
    $htmlstr .= "<h2>This Week</h2>";

    $htmlstr .= "<tr><td><b>Today</b></td></tr>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND scheduled_date = CURDATE() ORDER BY scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateRow($rowdata);
    }

    $htmlstr .= "<tr><td><b>Tomorrow</b></td></tr>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND scheduled_date = CURDATE() + INTERVAL 1 DAY ORDER BY scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateRow($rowdata);
    }

    $date = strtotime("tomorrow");
    for ($x = 2; $x < 7; $x++) {
      $date = strtotime("+1 day", $date);
      $datestr = date("l", $date);
      $htmlstr .= "<tr><td><b>" . $datestr . "</b></td></tr>";

      $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND scheduled_date = CURDATE() + INTERVAL ' . $x . ' DAY ORDER BY scheduled_time ASC');
      while ($rowdata = mysqli_fetch_array($data)) {
        generateRow($rowdata);
      }
    }
  }
  else if ($view == "month") {
    $htmlstr .= "<h2>This Month</h2>";
    $htmlstr .= "<div class='month'>";
    $thismonth = date("F Y", strtotime("today"));
    $htmlstr .= "<ul><li class='prev'>&#10094;</li><li class='next'>&#10095;</li><li>" . $thismonth . "</li></ul></div>";

    $htmlstr .= "<ul class='weekdays'>";
    $htmlstr .= "<li>MO</li><li>TU</li><li>WE</li><li>TH</li><li>FR</li><li>SA</li><li>SU</li>";
    $htmlstr .= "</ul>";

    $htmlstr .= "<ul class='days'>";

    $date = strtotime(date("Y-m-1"));
    $dayofweek = date("l", $date);
    if ($dayofweek == "Tuesday") {
      $htmlstr .= "<li></li>";
    }
    else if ($dayofweek == "Wednesday") {
      $htmlstr .= "<li></li><li></li>";
    }
    else if ($dayofweek == "Thursday") {
      $htmlstr .= "<li></li><li></li><li></li>";
    }
    else if ($dayofweek == "Friday") {
      $htmlstr .= "<li></li><li></li><li></li><li></li>";
    }
    else if ($dayofweek == "Saturday") {
      $htmlstr .= "<li></li><li></li><li></li><li></li><li></li>";
    }
    else if ($dayofweek == "Sunday") {
      $htmlstr .= "<li></li><li></li><li></li><li></li><li></li><li></li>";
    }

    $today = strtotime("today");
    $enddate = strtotime(date("Y-m-t"));
    $counter = 0;
    while ($date <= $enddate) {
      $append = "";
      $taskeventquery = "SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND scheduled_date = DATE_FORMAT(NOW() ,'%Y-%m-01') + INTERVAL " . $counter . " DAY";
      $counter++;
      $taskevents = mysqli_query($conn, $taskeventquery);
      $numtaskevents = mysqli_num_rows($taskevents);

      for ($i = 0; $i < $numtaskevents; $i++) {
        $append .= "<span class='taskevent'><br>!</span>";
      }

      $diff = $date - $today;
      if (floor($diff / (60*60*24)) == 0) {
        $htmlstr .= "<li><span class='current'>" . date("d", $date) . $append . "</span></li>";
      }
      else {
        $htmlstr .= "<li>" . date("d", $date) . $append ."</li>";
      }
      $date = strtotime("+1 day", $date);
    }

    $htmlstr .= "</ul>";
  }



  $htmlstr .= "</table><br><br>";

  echo $htmlstr;

  mysqli_close($conn);
?>
