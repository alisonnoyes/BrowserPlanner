<style>
  @import url("main.css");
</style>

<html>

  <head>
    <title>
      Alison's Planner
    </title>
  </head>

  <body style="background-color:#363535">

    <div class="textbox">
      <div class="centered">
        <h1><font color="white" face="helvetica">Alison's Planner</font></h1>
      </div>
    </div>

    <div align="left" class="smallmargin">
    <p>
      <button class="addnew" type="button" onclick="window.location.href='/newtask.php'">
        New Task
      </button>

      <button class="addnew" type="button" onclick="window.location.href='/newevent.php'">
        New Event
      </button>
    </p>

    <?php
      include "sqlsetup.php";

      echo "<div id='sql_data'>";

      // Tasks and events table
      $data = mysqli_query($conn, 'SELECT * FROM tasks_and_events');

      echo "<table border='1'> <tr>
        <th>Task name</th>
        <th>Due date</th>
        <th>Scheduled date</th>
        <th>Priority</th>
        <th>Project</th>
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
        echo "<td>" . $row["done"] . "</td>";
        echo "<td><span class='delete' id='del_" . $row["title"] . "'>Delete</span></td>";
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

          var id = splitid[1];

          // AJAX request
          $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { title: id },
            success: function(response) {
              if (response == 1) {
                // Row was removed from SQL, so remove from HTML
	              $(element).closest("tr").remove();

                // Reload the project table
                $.ajax({
                  url: 'tabledata.php',
                  type: 'POST',
                  data: { tablename: 'projects' },
                  success: function(response) {
                    var header = "<tr><th>Project name</th><th>Number of tasks and events</th></tr>";
                    $("#projecttable").html(header + response);
                  }
                })
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
