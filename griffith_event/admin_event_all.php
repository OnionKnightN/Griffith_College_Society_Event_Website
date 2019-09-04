<!--Header which contains the connection to the database and navigation bar-->
<?php include_once("header.php"); ?>
<?php
  // Validate for logged in users as Admin.
  if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
    if($_SESSION["user_type"] == "Admin"){
      $user_id = $_SESSION['user_id'];
    }else{
      //You not logged in as a Admin User
      header("Location: index.php?error=forbidden");
    }
  }else{
    //You have not logged into the website
    header("Location: login.php?error=forbidden");
  }
?>
<!--If statement that takes action if submit button is clicked named 'event'-->
<?php
  if(isset($_GET['event'])){
   //Gets status that was chosen by user from form.
   $status = $_GET['event_status'];
   // Status text and colour changes based on its value.
   if($status == 1){
     $event_stats = "<span style='color:Green;'><b>(APPROVED)</b></span>";
   }elseif($status == 2){
     $event_stats = "<span style='color:Red;'><b>(DISAPPROVED)</b></span>";
   }else{
     $event_stats = "<span style='color:Orange;'>(WAITING)</b></span>";
   }
 ?>
 <!--Beginning of event section -->
 <section class="event_container">
   <!--Heading of event with event status-->
   <h1>Griffith College Society Events <?php echo $event_stats?></h1>
   <!--GET method form to arrange event by status-->
   <form action="admin_event_all.php" method="GET" class="event_form">
     <select name="event_status" class="event_select">
       <option value= NULL>WAITING</option>
       <option value= 1>APPROVED</option>
       <option value= 2>DISAPPROVED</option>
     </select>
     <input type="submit" name="event" value="SUBMIT" class="event_btn">
   </form>
   <?php
     // Pull all events from database based on its status
     $pull_event = "SELECT event_id, event_name, event_image, event_location, event_date FROM tbl_event WHERE event_status = '$status' ";
     $pull_event_result =  mysqli_query($db_connect, $pull_event);
     // If database connection with query is true execute if statement
     if($pull_event_result){
       // Fetch information from database
       $row = mysqli_num_rows($pull_event_result);
       // Test if there is a information in event table.
       if($row>0){?>
        <section class="events">
        <!-- Does a while loop to get all events from database base on status  -->
        <?php while($row = mysqli_fetch_array($pull_event_result)){
           //Created variables based on information on the event tables.
           $event_id = $row['event_id'];
           $event_name = $row["event_name"];
           $event_image = $row['event_image'];
           $event_location = $row["event_location"];
           $event_month = date("M", strtotime($row['event_date']));
           $event_day = date("j", strtotime($row['event_date']));
           ?>
           <!--Created link based on each event through its event_id-->
           <a href="event_expand.php?event_id=<?php echo $event_id?>&event=SUBMIT" class="event-card">
             <!--Image based on event in the database-->
             <img src= "img/event_image/<?php echo $event_image?>" alt= "<?php echo $event_name?>">
             <!--Information based on event in the database-->
             <div class="event__content">
               <p class="event__date"><?php echo $event_day?><br> <?php echo $event_month?></p>
               <address class="event__address">
                 <span class="event__title"><?php echo $event_name?></span><br>
                 <?php echo $event_location.$status?>
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
<!--If no action has taken place show all events in database-->
<?php } else{?>
<section class="event_container">
  <h1>Griffith College Society Events</h1>
  <!--GET method form to arrange event by status-->
  <form action="admin_event_all.php" method="GET" class="event_form">
    <select name="event_status" class="event_select">
      <option value= NULL>WAITING</option>
      <option value= 1>APPROVED</option>
      <option value= 2>DISAPPROVED</option>
    </select>
    <p><input type="submit" name="event" value="SUBMIT" class="event_btn"></p>
  </form>
  <?php
    // Pull all events from database
    $pull_event = "SELECT event_id, event_name, event_image, event_location, event_date FROM tbl_event";
    $pull_event_result = mysqli_query($db_connect, $pull_event);
    // If database connection with query is true execute if statement
    if($pull_event_result){
      // Fetch information from database
      $row = mysqli_num_rows($pull_event_result);
      // Test if there is a information in event table.
      if($row>0){?>
        <section class="events">
        <!-- Does a while loop to get all events from database base on status  -->
        <?php while($row = mysqli_fetch_array($pull_event_result)){
          //Created variables based on information on the event tables.
          $event_id = $row['event_id'];
          $event_name = $row["event_name"];
          $event_image = $row['event_image'];
          $event_location = $row["event_location"];
          $event_month = date("M", strtotime($row['event_date']));
          $event_day = date("j", strtotime($row['event_date']));
          ?>
          <!--Created link based on each event through its event_id-->
          <a href="event_expand.php?event_id=<?php echo $event_id?>&event=SUBMIT" class="event-card">
            <!--Image based on event in the database-->
            <img src= "img/event_image/<?php echo $event_image?>" alt= "<?php echo $event_name?>">
            <!--Information based on event in the database-->
            <div class="event__content">
              <p class="event__date"><?php echo $event_day?><br> <?php echo $event_month?></p>
              <address class="event__address">
                <span class="event__title"><?php echo $event_name?></span><br>
                <?php echo $event_location?>
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
<?php } include_once("footer.php"); ?>
