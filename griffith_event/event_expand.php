<!--header that involves the navigation bar and connection to database-->
<?php require_once("header.php"); ?>
<?php
  // Validate for logged in users.
  if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
      $user_id = $_SESSION['user_id'];
  }else{
    //You not logged in as a Admin or Member.
    header("Location: login.php?error=forbidden");
  }
?>
<?php
  // Fetch soc_id and soc_name in database based on user_id
  $pull_society = "SELECT soc_id, soc_name FROM tbl_soc WHERE soc_president= $user_id";
  $pull_society_result =mysqli_query($db_connect, $pull_society);
  if($pull_society_result){
    // Fetch information from database
    mysqli_num_rows($pull_society_result);
    $row =mysqli_fetch_array($pull_society_result);
    // Created variable to sort soc_id and soc_name.
    $soc_id = $row["soc_id"];
    $soc_name = $row["soc_name"];
  }else{
    // Echo out if there are no Society listed.
    echo 'No Society Available';
  }

  // Status Event Form validation.
  if (isset($_POST["status"])) {
    // Number of errors at the beginning of form
    $errors = 0;
    // Event id validation.
    if (empty($_POST["event_id"])) {
      $event_idErr = "<p class ='error'>*Please select an event.</p>";
      $errors += 1;
    } else {
      $event_id = $_POST["event_id"];
    }
    // Event Status validation.
    if (empty($_POST["event_status"])) {
      $event_statusErr = "<p class ='error'>*Please select a event status.</p>";
      $errors += 1;
    } else {
      $event_status = $_POST["event_status"];
    }
    //SQL queries into database if there is zero errors and create POST Method submit
    if($errors == 0 && isset($_POST["status"])){
      // SQL query to Update event status based on event id.
      $query = "UPDATE tbl_event SET event_status = '$event_status' WHERE event_id = '$event_id'";
      if(mysqli_query($db_connect, $query)){
        //Redirect to current event page if successful.
        header("Location:event_expand.php?event_id=".$_POST["event_id"]."&event=SUBMIT");
      }else{
        //Testing Database connection
        echo "Unable to update event status in the database";
      }
    }
  }
?>
  <section class ="details_container">
    <section class = "details">
      <!--if you have searched a event from form execute if statement-->
      <?php if(isset($_GET['event'])){
        // Sort variable named Search on event id
        $search = $_GET['event_id'];
        // SQL Query to select all information on the event table based on event id
        $sql = "SELECT * FROM tbl_event WHERE event_id = '$search';";
        // SQL Query connection to database
        $result = mysqli_query($db_connect, $sql);
        // If there is a correct sql query execute
        if($result){
          //Get information from sql query
          $row = mysqli_fetch_assoc($result);
          // Description layout
          $description = $row['event_desc'];
          $length = strpos($description,'.',500);
          $paragraph_one= substr($description,0,$length);
          $paragraph_two= substr($description,$length +1);
          // Fetch image from database
          $event_image = $row['event_image'];
          // Fetch name from database
          $event_name = $row['event_name'];
          // Echo out results from event table.
          echo "<h1>".$row['event_name']." Event</h1>";
          echo "<p>$paragraph_one.</p>";
          echo "<p>$paragraph_two</p>";
          echo "<h1>Event Details</h1>";
          echo "<p><b>Location: </b>".$row['event_location']."</p>";
          echo "<p><b>Meetup Day: </b>".$row['event_date']."</p>";
          echo "<p><b>Start Time Day: </b>".$row['event_stime']."</p>";
          echo "<p><b>End Time: </b>".$row['event_etime']."</p>";

          // If Admin user present Event Status Form
          if($_SESSION["user_type"] == "Admin"){
            // Changing status value based on status selected.
            $status = $row['event_status'];
            if($status == 1){
              $status = "<span style='color:Green;'><b>Approved</b></span>";
            }elseif($status == 2){
              $status = "<span style='color:Red;'><b>Disapproved</b></span>";
            }else{
              $status = "<span style='color:Orange;'><b>Waiting</b></span>";
            }
            echo "<p><b>Status: </b>$status</p>";
          ?>
          <h1>Event Status</h1>
          <!--Form to change event status-->
          <form action="event_expand.php" method="post" class="details_form">
            <!--Listed out options of all events created-->
            <select name="event_id" class="details_select">
              <?php
                $pull_event = "SELECT event_id, event_name FROM tbl_event";
                $pull_event_result = mysqli_query($db_connect, $pull_event);
                if($pull_event_result){
                  $num = mysqli_num_rows($pull_event_result);
                  //  Test for valid test result
                 if($num>0){
                   while($row =mysqli_fetch_array($pull_event_result)){
                     // Echo out the events
                     echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
                   }
                 }else{
                   // Echo out if there are no events listed.
                   echo '<option>No events available</option>';
                 }
                }
              ?>
            </select>
            <p><select name="event_status" class="details_select">
              <option value=NULL>WAITING</option>
              <option value="1">APPROVED</option>
              <option value="2">DISAPPROVED</option>
            </select></p>
            <p><input type="submit" name="status" value="SUBMIT" class="details_btn" required></p>
          </form>
          <!--If Member or Society user present Event Search Form-->
        <?php }if($_SESSION["user_type"] == "Member" || $_SESSION["user_type"] == "Society"){ ?>
          <h1>Event Search</h1>
          <!--Form for Event Search -->
          <form action="event_expand.php" method="GET" class ="details_form">
            <!--Present all events that are active -->
            <select name="event_id" class="details_select">
              <?php
                $status = $row['event_status'];
                $pull_event = "SELECT event_id, event_name FROM tbl_event WHERE event_status = 1";
                $pull_event_result = mysqli_query($db_connect, $pull_event);
                if($pull_event_result){
                  $num = mysqli_num_rows($pull_event_result);
                  //  Test for valid test result
                  if($num>0){
                    while($row =mysqli_fetch_array($pull_event_result)){
                     // Echo out the events
                     echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
                    }
                  }else{
                    // Echo out if there are no events listed.
                    echo '<option>No Event Created</option>';
                  }
                }
              ?>
            </select>
            <p><input type="submit" name="event" value="SUBMIT" class="details_btn"></p>
          </form>
          <?php } ?>
        </section>
        <!--Show image based on event-->
        <?php echo "<div><img src='img/event_image/".$event_image."' alt='".$event_name."' class = 'details_img'></div>";?>
      <?php }?>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php } require_once("footer.php");?>
