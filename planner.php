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
      }

      body {
        margin: 0;
      }

      .header {
        background-color: #f1f1f1;
        padding: 20px;
        text-align: center;
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
    </style>
  </head>

  <body style="background-color:#363535">

    <div class="navbar">
      <left onclick="window.location.href='/BrowserPlanner/planner.php'">ALISON'S PLANNER</left>
      <right onclick="window.location.href='/BrowserPlanner/newtask.php'">New Task</right>
      <right onclick="window.location.href='/BrowserPlanner/newevent.php'">New Event</right>
    </div><br>

    <?php
      include "sqlsetup.php";

      echo "<div id='sql_data'>";

      // Tasks and events table
      $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events');

      echo "<table border='1' id='taskeventtable'> <tr>
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

      // Projects table
      $projectdata = mysqli_query($conn, 'SELECT * FROM projects');

      echo "<table border='1' id='projecttable'> <tr>
        <th>Project name</th>
        <th>Number of tasks and events</th>
        </tr>";

      while ($row = mysqli_fetch_array($projectdata)) {
        echo "<tr>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["count"] . "</td>";
        echo "</tr>";
      }

      echo "</table>";

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

  </body>

</html>
