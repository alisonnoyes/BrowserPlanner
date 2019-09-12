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
        width: 95%;
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

      .backgroundrect {
        margin-left: auto;
        margin-right: auto;
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
        <font onclick="#">Today</font><br><br>
        <font onclick="#">This week</font><br><br>
        <font onclick="#">This month</font><br><br>
        <font onclick="#">All tasks and events</font><br><br>
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
          <form action="newtask.php" method="POST">
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

                echo "<script>window.location.href='/BrowserPlanner/planner.php'</script>";
              }
            ?>
          </form>
        </div>

        <div id="addnewevent" style="display:block">
          <a href="javascript:swapDiv('addnewevent', 'neweventform')">New event</a>
        </div>

        <div id="neweventform" style="display:none">
          <form action="newevent.php" method="POST">
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

                echo "<script>window.location.href='/BrowserPlanner/planner.php'</script>";
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

          echo "<div id='sql_data'>";

          // Tasks and events table
          $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events');

          echo "<table class='datatable' id='taskeventtable'> <tr>
            <th>Task name</th>
            <th>Due date</th>
            <th>Scheduled date</th>
            <th>Priority</th>
            <th>Project</th>
            <th>Parent task/event</th>
            <th>Done?</th>
            <th></th>
            </tr>";

          while ($row = mysqli_fetch_array($data)) {
            echo "<tr>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["due_date"] . "</td>";
            echo "<td>" . $row["scheduled_date"] . "</td>";
            echo "<td>" . $row["priority"] . "</td>";
            echo "<td>" . $row["project"] . "</td>";
            echo "<td>" . $row["parent"] . "</td>";
            echo "<td>" . $row["done"] . "</td>";
            echo "<td><span class='delete' id='del_" . $row["id"] . "'>Delete</span></td>";
            echo "</tr>";
          }

          echo "</table><br><br>";

          echo "</div>";

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
                        var header = "<tr><th>Task name</th><th>Due date</th><th>Scheduled date</th><th>Priority</th><th>Project</th><th>Parent task/event</th><th>Done?</th><th></th></tr>";
                        $("#taskeventtable").html(header + response);
                      }
                    });

                    // Reload the project table
                    $.ajax({
                      url: 'tabledata.php',
                      type: 'POST',
                      data: { tablename: 'projects' },
                      success: function(response) {
                        var header = "<tr><th>Project name</th><th>Number of tasks and events</th></tr>";
                        $("#projecttable").html(header + response);
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
