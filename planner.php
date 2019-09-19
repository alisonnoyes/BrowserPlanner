<html lang="en">

  <head>
    <title>
      alison's planner
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
        color: white;
      }

      ul {
        list-style-type: none;
      }

      a:link {
        text-decoration: none;
        color: white;
      }
      a:visited {
        text-decoration: none;
        color: white;
      }

      .navbar {
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.25);
        box-shadow: 2px 2px 5px #1f1e1e;
        height: 64px;
      }
        .navbar left {
          float: left;
          display: block;
          color: #f2f2f2;
          text-align: center;
          padding: 16px 30px;
          text-decoration: none;
          height: 100%;
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
          padding: 22px 30px;
          height: 100%;
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

      .circle {
        height: 10px;
        width: 10px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
      }

      .tablerow {
        overflow: hidden;
      }
        .tablerow left {
          float: left;
          display: block;
          text-align: left;
          padding: 16px 10px;
        }
        .tablerow right {
          float: right;
          display: block;
          text-align: left;
          padding: 16px 10px;
        }
    </style>
  </head>

  <body style="background-color:#363535">

    <div class="navbar">
      <left onclick="window.location.href='/BrowserPlanner/planner.php?'"><b><font size="5">alison's planner</font></b></left>
      <right>
        <div id="addnewtask" style="display: block">
          <a href="javascript:swapDiv('addnewtask', 'newtaskform')">NEW TASK</a>
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
      </right>

      <right>
        <div id="addnewevent" style="display:block">
          <a href="javascript:swapDiv('addnewevent', 'neweventform')">NEW EVENT</a>
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
      </right>
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
            echo "<td><span class='circle' style='background-color:" . $row["color"] . "'></span></td>";
            echo "<td><a href=?" . str_replace(" ", "-", $row["title"]) . ">" . $row["title"] . "</td>";
            echo "<td>" . $row["count"] . "</td>";
            echo "</tr>";
          }

          echo "</table>";
        ?>
      </div>

      <div id="rightitem">
        <br>
        <div id="sql_data">
        </div>

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

          if ($_POST && isset($_POST["time"])) {
            $input_time = $_POST["time"];
            $sqltime = date("H:i:s", strtotime($input_time));

            $querystring = 'UPDATE tasks_and_events SET scheduled_time=CAST("' . $sqltime . '" AS TIME) WHERE id=' . $_POST['id'];
            mysqli_query($conn, $querystring);
            $url = basename($_SERVER["REQUEST_URI"]);
            echo "<script>window.location.href=" . $url . "</script>";
          }

          mysqli_close($conn);
        ?>

        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script>
          $(document).ready(function() {
            $.ajax({
              url: 'loadview.php',
              type: 'POST',
              data: { url: window.location.href },
              success: function(response) {
                $("#sql_data").html(response);
              }
            });

            $('#sql_data').on("click", ".done", function() {
              var element = this;
              var fullid = this.id;
              var splitid = fullid.split("_");

              var myid = splitid[1];

              $.ajax({
                url: 'done.php',
                type: 'POST',
                data: { id: myid },
                success: function(response) {
                  if (response == 1) {
                    // Reload the task and event table
                    $.ajax({
                      url: 'loadview.php',
                      type: 'POST',
                      data: { url: window.location.href },
                      success: function(response) {
                        $("#sql_data").html(response);
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
            })
            $('#sql_data').on("click", ".delete", function() {
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
                      url: 'loadview.php',
                      type: 'POST',
                      data: { url: window.location.href },
                      success: function(response) {
                        $("#sql_data").html(response);
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
