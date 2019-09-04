<!--Header which contains the connection to the database and navigation bar-->
<?php include_once("header.php"); ?>
<?php
  // Validate for logged in users as Member.
  if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
      $user_id = $_SESSION['user_id'];
  }else{
    //You have not logged into the website
    header("Location: login.php?error=forbidden");
  }
?>
  <section class = "event_container">
    <h1>GRIFFITH COLLEGE SOCIETY EVENTS</h1>
    <?php
      // Query that selects information on events that are approved.
      $pull_event = "SELECT event_id, event_name, event_image, event_location, event_date FROM tbl_event WHERE event_status = '1'";
      $pull_event_result = mysqli_query($db_connect, $pull_event);
      // If database connection with query is true execute if statement
      if($pull_event_result){
        // Fetch information from database
        $row = mysqli_num_rows($pull_event_result);
        //  Test for valid test result
        if($row>0){?>
          <section class="events">
          <!--Does a while loop to get all events from database that are approved-->
          <?php while($row = mysqli_fetch_array($pull_event_result)){
            // Stores information of the events in variables
            $event_id = $row['event_id'];
            $event_name = $row["event_name"];
            $event_image = $row['event_image'];
            $event_location = $row["event_location"];
            $event_month = date("M", strtotime($row['event_date']));
            $event_day = date("j", strtotime($row['event_date']));
            ?>
            <!--Created a event card link with event information-->
            <a href="event_expand.php?event_id=<?php echo $event_id?>&event=SUBMIT" class="event-card">
              <img src= "img/event_image/<?php echo $event_image?>" alt= "<?php echo $event_name?>">
              <div class="event__content">
                <p class="event__date"><?php echo $event_day?><br> <?php echo $event_month?></p>
                <address class="event__address">
                  <span class="event__title"><?php echo $event_name?></span><br>
                </address>
              </div>
            </a>
            <?php
          }
        }else{
          // Echo out if there are no Events listed.
          echo '<h3>No events available</h3>';
        }
      }
    ?>
  </section>
</section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); ?>
