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
        <input type="submit" value="Submit" />

        <?php
          if ($_POST && isset($_POST['name']) && isset($_POST['date'])) {
            include "sqlsetup.php";

            $input_date = $_POST['date'];
            $date = date("Y-m-d",strtotime($input_date));

            $querystring = 'INSERT INTO tasks_and_events (title, due_date, scheduled_date, priority, done, project)' .
            ' VALUES ("' . $_POST['name'] . '", NOW(), STR_TO_DATE("' .
            $date . '", "%Y-%m-%d"), ' . $_POST['priority'] . ', 99, "' . $_POST['project'] . '")';
            mysqli_query($conn, $querystring);

            if ($_POST['project'] != "none") {
              $prevcount = mysqli_query($conn, "SELECT count FROM projects WHERE title='" . $_POST['project'] . "'");
              $count = array_values(mysqli_fetch_assoc($prevcount))[0];
              $projcountquery = "UPDATE projects SET count=" . ($count + 1) . " WHERE title='" . $_POST['project'] . "'";
              mysqli_query($conn, $projcountquery);
            }

            mysqli_close($conn);

            echo "<script>window.location.href='/planner.php'</script>";
          }
        ?>
      </form>
    </p>

  </body>
