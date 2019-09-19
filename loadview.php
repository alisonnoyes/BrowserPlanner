<?php
  include "sqlsetup.php";

  $htmlstr = "";

  function generateDayRow($row) {
    global $htmlstr;

    $htmlstr .= "<tr><td>";
    $htmlstr .= "<div class='tablerow'>";
    $htmlstr .= "<right><span class='delete' id='del_" . $row["id"] . "'>&#xe020;</span></right>";

    // Not an event
    if ($row["done"] != 99) {
      $color = "white";
      if ($row["priority"] == 1) { $color = "red"; }
      else if ($row["priority"] == 2) { $color = "yellow"; }
      else if ($row["priority"] == 3) { $color = "blue"; }

      $htmlstr .= "<left><span class='done' id='done_" . $row["id"] . "'><span class='circle' style='border:2px solid " . $color . "; background-color: #363535; height:15px; width:15px'></span></left>";

      $htmlstr .= "<right>Due " . $row["due_date"] . "</right>";
    }

    $htmlstr .= "<left>" . $row["title"] . "</left>";

    $projcolor = "white";
    if ($row["project"] != "none") {
      global $conn;
      $projinfo = mysqli_query($conn, "SELECT * FROM projects WHERE title='" . $row["project"] . "'");
      while ($i = mysqli_fetch_array($projinfo)) {
        $projcolor = $i["color"];
      }
    }
    $htmlstr .= "<right><font color='" . $projcolor . "'>" . $row["project"] . "</font></right>";

    $htmlstr .= "</div></td></tr>";
  }

  function generateProjectRow($row) {
    global $htmlstr;

    $htmlstr .= "<tr><td>";
    $htmlstr .= "<div class='tablerow'>";
    $htmlstr .= "<right><span class='delete' id='del_" . $row["id"] . "'>&#xe020;</span></right>";

    // Not an event
    if ($row["done"] != 99) {
      $color = "white";
      if ($row["priority"] == 1) { $color = "red"; }
      else if ($row["priority"] == 2) { $color = "yellow"; }
      else if ($row["priority"] == 3) { $color = "blue"; }

      $htmlstr .= "<left><span class='done' id='done_" . $row["id"] . "'><span class='circle' style='border:2px solid " . $color . "; background-color: #363535; height:15px; width:15px'></span></left>";

      $htmlstr .= "<right>Due " . $row["due_date"] . "</right><right>//</right>";
    }

    $htmlstr .= "<left>" . $row["title"] . "</left>";
    $htmlstr .= "<right>" . $row["scheduled_date"] . " " . $row["scheduled_time"] . "</right>";

    $htmlstr .= "</div></td></tr>";
  }

  function generateRow($row) {
    global $htmlstr;

    $htmlstr .= "<tr><td>";
    $htmlstr .= "<div class='tablerow'>";
    $htmlstr .= "<right><span class='delete' id='del_" . $row["id"] . "'>&#xe020;</span></right>";

    // Not an event
    if ($row["done"] != 99) {
      $color = "white";
      if ($row["priority"] == 1) { $color = "red"; }
      else if ($row["priority"] == 2) { $color = "yellow"; }
      else if ($row["priority"] == 3) { $color = "blue"; }

      $htmlstr .= "<left><span class='done' id='done_" . $row["id"] . "'><span class='circle' style='border:2px solid " . $color . "; background-color: #363535; height:15px; width:15px'></span></left>";

      $htmlstr .= "<right>Due " . $row["due_date"] . "</right><right>//</right>";
    }

    $htmlstr .= "<left>" . $row["title"] . "</left>";
    $htmlstr .= "<right>" . $row["scheduled_date"] . " " . $row["scheduled_time"] . "</right>";
    $projcolor = "white";
    if ($row["project"] != "none") {
      global $conn;
      $projinfo = mysqli_query($conn, "SELECT * FROM projects WHERE title='" . $row["project"] . "'");
      while ($i = mysqli_fetch_array($projinfo)) {
        $projcolor = $i["color"];
      }
    }
    $htmlstr .= "<right><font color='" . $projcolor . "'>" . $row["project"] . "</font></right>";

    $htmlstr .= "</div></td></tr>";
  }

  // Tasks and events table
  $htmlstr .= "<table class='datatable' id='taskeventtable'>";

  $data = "";
  $url = $_POST["url"];
  if (isset($_POST["filter"])) {
    $filter = $_POST["filter"];
  }
  else {
    $filter = "1=1";
  }
  $view = substr($url, strpos($url, "?") + 1);
  if ($view == "lanner.php" || $view == "") {
    $htmlstr .= "<h2>All Tasks and Events</h2>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND ' . $filter . ' ORDER BY DATE(scheduled_date) ASC, scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateRow($rowdata);
    }
  }
  else if ($view == "today") {
    $htmlstr .= "<h2>Today</h2>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND ' . $filter . ' AND scheduled_date = CURDATE() ORDER BY scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateDayRow($rowdata);
    }
  }
  else if ($view == "week") {
    $htmlstr .= "<h2>This Week</h2>";

    $htmlstr .= "<tr><td><b>Today</b></td></tr>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND ' . $filter . ' AND scheduled_date = CURDATE() ORDER BY scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateDayRow($rowdata);
    }

    $htmlstr .= "<tr><td><b>Tomorrow</b></td></tr>";
    $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND ' . $filter . ' AND scheduled_date = CURDATE() + INTERVAL 1 DAY ORDER BY scheduled_time ASC');
    while ($rowdata = mysqli_fetch_array($data)) {
      generateDayRow($rowdata);
    }

    $date = strtotime("tomorrow");
    for ($x = 2; $x < 7; $x++) {
      $date = strtotime("+1 day", $date);
      $datestr = date("l", $date);
      $htmlstr .= "<tr><td><b>" . $datestr . "</b></td></tr>";

      $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND ' . $filter . ' AND scheduled_date = CURDATE() + INTERVAL ' . $x . ' DAY ORDER BY scheduled_time ASC');
      while ($rowdata = mysqli_fetch_array($data)) {
        generateDayRow($rowdata);
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
      $taskeventquery = "SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND " . $filter . " AND scheduled_date = DATE_FORMAT(NOW() ,'%Y-%m-01') + INTERVAL " . $counter . " DAY";
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
  else {
    $proj = str_replace("-", " ", $view);

    $htmlstr .= "<h2>" . $proj . "</h2>";
    $querystring = 'SELECT * FROM tasks_and_events WHERE (done = 0 OR done = 99) AND project="' . $proj . '" AND ' . $filter . ' ORDER BY DATE(scheduled_date) ASC, scheduled_time ASC';
    $data = mysqli_query($conn, $querystring);
    while ($rowdata = mysqli_fetch_array($data)) {
      generateProjectRow($rowdata);
    }
  }

  $htmlstr .= "</table><br><br>";

  echo $htmlstr;

  mysqli_close($conn);
?>
