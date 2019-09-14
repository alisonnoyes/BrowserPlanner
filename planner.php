<html lang="en">

  <head>
    <title>
      Alison's Planner
    </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      * {
        box-sizing: border-box;
        font-family: sans-serif;
      }

      body {
        margin: 0;
      }

      ul {
        list-style-type: none;
      }

      .navbar {
        overflow: hidden;
        background-color: #363535;
        box-shadow: 2px 2px 5px #1f1e1e;
      }
        .navbar left {
          float: left;
          display: block;
          color: #f2f2f2;
          text-align: center;
          padding: 16px 30px;
          text-decoration: none;
        }
          .navbar left:hover {
            background-color: #ddd;
            color: black;
          }
        .navbar right {
          float: right;
          display: block;
          color: #f2f2f2;
          text-align: center;
          padding: 16px 30px;
          text-decoration: none;
        }
          .navbar right:hover {
            background-color: #ddd;
            color: black;
          }

      #taskeventtable {
        border-collapse: collapse;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
      }
        #taskeventtable td, #taskeventtable th {
          border-bottom: 1px solid #363535;
          border-top: 1px solid #363535;
          border-collapse: collapse;
          padding: 8px;
        }
        #taskeventtable tr:hover {
          background-color: #363535;
        }
        #taskeventtable th {
          padding-top: 12px;
          padding-bottom: 12px;
          text-align: left;
          background-color: #363535;
          color: #ddd;
        }

      #container {
        width: 100%;
        margin: auto;
      }
      #pad {
        width: 10%;
        float: left;
        height: 100%;
      }
      #leftitem {
        width: 15%;
        float: left;
        height: 100%;
      }
      #rightitem {
        width: 65%;
        float: left;
        height: 100%;
        background-color: #1f1e1e;
        padding-left: 24px;
        padding-right: 24px;
      }

      .month {
        padding: 70px 25px;
        width: 100%;
        background: #141414;
        text-align: center;
      }
        .month ul {
          margin: 0;
          padding: 0;
        }
          .month ul li {
            color: white;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 3px;
          }
        .month .prev {
          float: left;
          padding-top: 10px;
        }
        .month .next {
          float: right;
          padding-top: 10px;
        }
      .weekdays {
        margin: 0;
        padding: 10px 0;
        background-color: #9da0ab;
      }
        .weekdays li {
          display: inline-block;
          width: 13.6%;
          color: #52545c;
          text-align: center;
        }
      .days {
        margin: 0;
        padding: 20px 0;
        background: #cccfdb;
      }
        .days li {
          list-style-type: none;
          display: inline-block;
          width: 13.6%;
          text-align: center;
          margin-bottom: 20px;
          font-size: 14px;
          color: #52545c;
        }
          .days li .current {
            padding: 5px;
            background: #9da0ab;
            color: white !important;
          }
          .days li .taskevent {
            float: center;
            padding-bottom: 5px;
          }
    </style>
  </head>

  <body style="background-color:#363535">

    <div class="navbar">
      <left onclick="window.location.href='/BrowserPlanner/planner.php'"><b>ALISON'S PLANNER</b></left>
      <right onclick="window.location.href='/BrowserPlanner/newtask.php'">New Task</right>
      <right onclick="window.location.href='/BrowserPlanner/newevent.php'">New Event</right>
    </div>

    <div id="container">
      <div id="pad"></div>

      <div id="leftitem">
        <br><br>
        <a href="?today">Today</a><br><br>
        <a href="?week">This week</a><br><br>
        <a href="?month">This month</a><br><br>
        <a href="?">All tasks and events</a><br><br>
        <?php
          include "sqlsetup.php";

          // Projects table
          $projectdata = mysqli_query($conn, 'SELECT * FROM projects');

          echo "<b>Projects</b>";
          echo "<table class='datatable' id='projecttable'>";

          while ($row = mysqli_fetch_array($projectdata)) {
            echo "<tr>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["count"] . "</td>";
            echo "</tr>";
          }

          echo "</table>";
        ?>
      </div>

      <div id="rightitem">
        <br>

        <div id="addnewtask" style="display: block">
          <a href="javascript:swapDiv('addnewtask', 'newtaskform')">New task</a>
        </div>

        <div id="newtaskform" style="display: none">
          <form action="planner.php" method="POST">
            <font color="white" face="helvetica">Task name: </font> <input type="text" name="name" /> <br><br>
            <font color="white" face="helvetica">Due date: </font> <input type="date" name="duedate" /> <br><br>
            <font color="white" face="helvetica">Scheduled date: </font> <input type="date" name="dodate" /> <br><br>
            <font color="white" face="helvetica">Priority: </font> <input type="number" name="priority" /> <br><br>
            <font color="white" face="helvetica">Project: </font> <select name="project">
              <option value="none">None</option>
              <?php
                include "sqlsetup.php";

                $proj = mysqli_query($conn, "SELECT * FROM projects");
                while ($row = mysqli_fetch_array($proj)) {
                  echo "<option value='" . $row["title"] . "'>" . $row["title"] . "</option>";
                }
              ?>
            </select> <br><br>
            <font color="white" face="helvetica">Parent task/event: </font> <select name="parent">
              <option value=0>None</option>
              <?php
                include "sqlsetup.php";

                $proj = mysqli_query($conn, "SELECT * FROM tasks_and_events");
                while ($row = mysqli_fetch_array($proj)) {
                  echo "<option value='" . $row["id"] . "'>" . $row["title"] . "</option>";
                }
              ?>
            </select> <br><br>
            <input type="submit" value="Submit" />

            <?php
              if ($_POST && isset($_POST['name']) && isset($_POST['duedate'])) {
                include "sqlsetup.php";

                $input_duedate = $_POST['duedate'];
                $duedate = date("Y-m-d",strtotime($input_duedate));
                $input_dodate = $_POST['dodate'];
                $dodate = date("Y-m-d", strtotime($input_dodate));

                $idno = date('YmdHis');

                $querystring = 'INSERT INTO tasks_and_events (title, due_date, scheduled_date, priority, done, project, parent, id)' .
                ' VALUES ("' . $_POST['name'] . '", STR_TO_DATE("' . $duedate . '", "%Y-%m-%d"), STR_TO_DATE("' .
                $dodate . '", "%Y-%m-%d"), ' . $_POST['priority'] . ', 0, "' . $_POST['project'] . '", "' . $_POST['parent'] . '", ' . $idno . ')';
                mysqli_query($conn, $querystring);

                if ($_POST['project'] != "none") {
                  $prevcount = mysqli_query($conn, "SELECT count FROM projects WHERE title='" . $_POST['project'] . "'");
                  $count = array_values(mysqli_fetch_assoc($prevcount))[0];
                  $projcountquery = "UPDATE projects SET count=" . ($count + 1) . " WHERE title='" . $_POST['project'] . "'";
                  mysqli_query($conn, $projcountquery);
                }

                mysqli_close($conn);

                $url = basename($_SERVER["REQUEST_URI"]);
                echo "<script>window.location.href=" . $url . "</script>";
              }
            ?>
          </form>
        </div>

        <div id="addnewevent" style="display:block">
          <a href="javascript:swapDiv('addnewevent', 'neweventform')">New event</a>
        </div>

        <div id="neweventform" style="display:none">
          <form action="planner.php" method="POST">
            <font color="white" face="helvetica">Event name: </font> <input type="text" name="name" /> <br><br>
            <font color="white" face="helvetica">Date: </font> <input type="date" name="date" /> <br><br>
            <font color="white" face="helvetica">Priority: </font> <input type="number" name="priority" /> <br><br>
            <font color="white" face="helvetica">Project: </font> <select name="project">
              <option value="none">None</option>
              <?php
                include "sqlsetup.php";

                $proj = mysqli_query($conn, "SELECT * FROM projects");
                while ($row = mysqli_fetch_array($proj)) {
                  echo "<option value='" . $row["title"] . "'>" . $row["title"] . "</option>";
                }
              ?>
            </select> <br><br>
            <font color="white" face="helvetica">Parent task/event: </font> <select name="parent">
              <option value=0>None</option>
              <?php
                include "sqlsetup.php";

                $proj = mysqli_query($conn, "SELECT * FROM tasks_and_events");
                while ($row = mysqli_fetch_array($proj)) {
                  echo "<option value='" . $row["id"] . "'>" . $row["title"] . "</option>";
                }
              ?>
            </select> <br><br>
            <input type="submit" value="Submit" />

            <?php
              if ($_POST && isset($_POST['name']) && isset($_POST['date'])) {
                include "sqlsetup.php";

                $input_date = $_POST['date'];
                $date = date("Y-m-d",strtotime($input_date));

                $idno = date('YmdHis');

                $querystring = 'INSERT INTO tasks_and_events (title, due_date, scheduled_date, priority, done, project, parent, id)' .
                ' VALUES ("' . $_POST['name'] . '", NOW(), STR_TO_DATE("' .
                $date . '", "%Y-%m-%d"), ' . $_POST['priority'] . ', 99, "' . $_POST['project'] . '", "' . $_POST['parent'] . '", ' . $idno . ')';
                mysqli_query($conn, $querystring);

                if ($_POST['project'] != "none") {
                  $prevcount = mysqli_query($conn, "SELECT count FROM projects WHERE title='" . $_POST['project'] . "'");
                  $count = array_values(mysqli_fetch_assoc($prevcount))[0];
                  $projcountquery = "UPDATE projects SET count=" . ($count + 1) . " WHERE title='" . $_POST['project'] . "'";
                  mysqli_query($conn, $projcountquery);
                }

                mysqli_close($conn);

                $url = basename($_SERVER["REQUEST_URI"]);
                echo "<script>window.location.href=" . $url . "</script>";
              }
            ?>
          </form>
        </div>
        <br>

        <script type="text/javascript">
          function swapDiv(d1, d2) {
            div1 = document.getElementById(d1);
            div2 = document.getElementById(d2);

            if (div2.style.display == "none") {
              div1.style.display = "none";
              div2.style.display = "block";
            }
            else {
              div1.style.display = "block";
              div2.style.display = "none";
            }
          }
        </script>

        <?php
          include "sqlsetup.php";

          function generateRow($row) {
            echo "<tr>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["due_date"] . "</td>";
            echo "<td>" . $row["scheduled_date"] . "</td>";
            echo "<td>" . $row["scheduled_time"] . "</td>";
            echo "<td>" . $row["priority"] . "</td>";
            echo "<td>" . $row["project"] . "</td>";
            echo "<td>" . $row["parent"] . "</td>";
            echo "<td>" . $row["done"] . "</td>";
            echo "<td>";
            echo "<span class='delete' id='del_" . $row["id"] . "'>Delete</span><br>";
            echo "<div id='addtime' style='display: block'><a href='javascript:swapDiv(\"addtime\", \"timeform\")'>Add time</a></div>";
            echo "<div id='timeform' style='display: none'>";
            echo "<form action='planner.php' method='POST'>";
            echo "<font color='white' face='helvetica'>Time: </font> <input type='time' name='time'/>";
            echo "<input type='number' name='id' style='display: none' value=" . $row["id"] . "></input>";
            echo "<input type='submit' value='Submit' />";
            echo "</form></div></td>";
            echo "</tr>";
          }

          echo "<div id='sql_data'>";

          // Tasks and events table
          echo "<table class='datatable' id='taskeventtable'>";

          $data = "";
          $url = basename($_SERVER["REQUEST_URI"]);
          $view = substr($url, strpos($url, "?") + 1);
          if ($view == "lanner.php" || $view == "") {
            echo "<h2>All Tasks and Events</h2>";
            $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events ORDER BY DATE(scheduled_date) ASC, scheduled_time ASC');
            while ($rowdata = mysqli_fetch_array($data)) {
              generateRow($rowdata);
            }
          }
          else if ($view == "today") {
            echo "<h2>Today</h2>";
            $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE scheduled_date = CURDATE() ORDER BY scheduled_time ASC');
            while ($rowdata = mysqli_fetch_array($data)) {
              generateRow($rowdata);
            }
          }
          else if ($view == "week") {
            echo "<h2>This Week</h2>";

            echo "<tr><td><b>Today</b></td></tr>";
            $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE scheduled_date = CURDATE() ORDER BY scheduled_time ASC');
            while ($rowdata = mysqli_fetch_array($data)) {
              generateRow($rowdata);
            }

            echo "<tr><td><b>Tomorrow</b></td></tr>";
            $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE scheduled_date = CURDATE() + INTERVAL 1 DAY ORDER BY scheduled_time ASC');
            while ($rowdata = mysqli_fetch_array($data)) {
              generateRow($rowdata);
            }

            $date = strtotime("tomorrow");
            for ($x = 2; $x < 7; $x++) {
              $date = strtotime("+1 day", $date);
              $datestr = date("l", $date);
              echo "<tr><td><b>" . $datestr . "</b></td></tr>";

              $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events WHERE scheduled_date = CURDATE() + INTERVAL ' . $x . ' DAY ORDER BY scheduled_time ASC');
              while ($rowdata = mysqli_fetch_array($data)) {
                generateRow($rowdata);
              }
            }
          }
          else if ($view == "month") {
            echo "<h2>This Month</h2>";
            echo "<div class='month'>";
            $thismonth = date("F Y", strtotime("today"));
            echo "<ul><li class='prev'>&#10094;</li><li class='next'>&#10095;</li><li>" . $thismonth . "</li></ul></div>";

            echo "<ul class='weekdays'>";
            echo "<li>MO</li><li>TU</li><li>WE</li><li>TH</li><li>FR</li><li>SA</li><li>SU</li>";
            echo "</ul>";

            echo "<ul class='days'>";

            $date = strtotime(date("Y-m-1"));
            $dayofweek = date("l", $date);
            if ($dayofweek == "Tuesday") {
              echo "<li></li>";
            }
            else if ($dayofweek == "Wednesday") {
              echo "<li></li><li></li>";
            }
            else if ($dayofweek == "Thursday") {
              echo "<li></li><li></li><li></li>";
            }
            else if ($dayofweek == "Friday") {
              echo "<li></li><li></li><li></li><li></li>";
            }
            else if ($dayofweek == "Saturday") {
              echo "<li></li><li></li><li></li><li></li><li></li>";
            }
            else if ($dayofweek == "Sunday") {
              echo "<li></li><li></li><li></li><li></li><li></li><li></li>";
            }

            $today = strtotime("today");
            $enddate = strtotime(date("Y-m-t"));
            $counter = 0;
            while ($date <= $enddate) {
              $append = "";
              $taskeventquery = "SELECT * FROM tasks_and_events WHERE scheduled_date = CURDATE() + INTERVAL " . $counter . " DAY";
              $counter++;
              $taskevents = mysqli_query($conn, $taskeventquery);
              $numtaskevents = mysqli_num_rows($taskevents);

              for ($i = 0; $i < $numtaskevents; $i++) {
                $append .= "<span class='taskevent'><br>!</span>";
              }

              $diff = $date - $today;
              if (floor($diff / (60*60*24)) == 0) {
                echo "<li><span class='current'>" . date("d", $date) . $append . "</span></li>";
              }
              else {
                echo "<li>" . date("d", $date) . $append ."</li>";
              }
              $date = strtotime("+1 day", $date);
            }

            echo "</ul>";
          }



          echo "</table><br><br>";

          echo "</div>";

          if ($_POST && isset($_POST["time"])) {
            $input_time = $_POST["time"];
            $sqltime = date("H:i:s", strtotime($input_time));

            $querystring = 'UPDATE tasks_and_events SET scheduled_time=CAST("' . $sqltime . '" AS TIME) WHERE id=' . $_POST['id'];
            mysqli_query($conn, $querystring);
            echo "<script>window.location.href='/BrowserPlanner/planner.php'</script>";
          }

          mysqli_close($conn);
        ?>

        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script>
          $(document).ready(function() {
            $('.delete').click(function() {
              var element = this;
              var fullid = this.id;
              var splitid = fullid.split("_");

              var myid = splitid[1];

              $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: { id: myid },
                success: function(response) {
                  if (response == 1) {
                    // Reload the task and event table
                    $.ajax({
                      url: 'tabledata.php',
                      type: 'POST',
                      data: { tablename: 'tasks_and_events' },
                      success: function(response) {
                        $("#taskeventtable").html(response);
                      }
                    });

                    // Reload the project table
                    $.ajax({
                      url: 'tabledata.php',
                      type: 'POST',
                      data: { tablename: 'projects' },
                      success: function(response) {
                        $("#projecttable").html(response);
                      }
                    });
                  }
                  else {
                    alert(response);
                  }
                }
              });
            });
          });
        </script>

      </div>

      <div id="pad"></div>
    </div>

  </body>

</html>
