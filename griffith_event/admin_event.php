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
  // Declaring global variables
  // Input of information of Event Form
  $event_id = "";
  $event_name = "";
  $event_creator = "";
  $event_desc = "";
  $event_location = "";
  $event_image = "";
  $event_update = date("Y-m-d");
  $event_date = "";
  $event_stime = "";
  $event_etime = "";
  $event_status = "";
  $event_confirm = "";
  // Error Messages of Event Form
  $event_idErr = "";
  $event_nameErr = "";
  $event_creatorErr = "";
  $event_descErr = "";
  $event_locationErr = "";
  $event_imageErr = "";
  $event_dateErr = "";
  $event_stimeErr = "";
  $event_etimeErr = "";
  $event_statusErr = "";
  $event_confirmErr = "";
  // Error set to 1 before submission
  $errors = 1;
  //Sanitize the data input from the user.
  function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($GLOBALS["db_connect"], $data);
    return $data;
  }
  // Update Event Form validation.
  if (isset($_POST["update"])) {
    // Number of errors at the beginning of form
    $errors = 0;
    // Event id validation.
    if (empty($_POST["event_id"])) {
      $event_idErr = "<p class ='error'>*Please select an event.</p>";
      $errors += 1;
    } else {
      $event_id = test_input($_POST["event_id"]);
    }
    // Event Name validation.
    if (empty($_POST["event_name"])) {
      $event_nameErr = "<p class ='error'>*Event name is required.</p>";
      $errors += 1;
    }else{
      $event_name = test_input($_POST["event_name"]);
      if (!preg_match("/^[a-zA-Z ]/", $event_name)) {
        $event_nameErr = "<p class ='error'>*Invalid event name.</p>";
        $errors += 1;
      }
    }
    // Event Creator validation.
    if (empty($_POST["event_creator"])) {
      $event_creatorErr = "<p class ='error'>*Event organiser name is required.</p>";
      $errors += 1;
    }else{
      $event_creator = test_input($_POST["event_creator"]);
      if (!preg_match("/^[a-zA-Z ]/", $event_creator)) {
        $event_creatorErr = "<p class ='error'>*Invalid event organiser name.</p>";
        $errors += 1;
      }
    }
    // Event Description validation.
    if (empty($_POST["event_desc"])) {
      $event_descErr = "<p class ='error'>*Event description is required.</p>";
      $errors += 1;
    }else{
      $event_desc = test_input($_POST["event_desc"]);
      if (!preg_match("/^[a-zA-Z0-9 ]/", $event_desc)) {
        $event_descErr = "<p class ='error'>*Invalid Event description.</p>";
        $errors += 1;
      }
    }
    // Event Location validation.
    if (empty($_POST["event_location"])) {
      $event_locationErr = "<p class ='error'>*Event location is required.</p>";
      $errors += 1;
    } else {
      $event_location = test_input($_POST["event_location"]);
      if (!preg_match("/[1-9]+[a-zA-Z1-9 ]+/", $event_location)) {
        $event_locationErr = "<p class ='error'>*Invalid event location.</p>";
        $errors += 1;
      }
    }
    // Meet up day validation.
    if (empty($_POST["event_date"])) {
      $event_dateErr = "<p class ='error'>*Please select a event date.</p>";
      $errors += 1;
    } else {
      $event_date = test_input($_POST["event_date"]);
      if(date("Y-m-d") > $event_date){
        $event_dateErr = "<p class ='error'>*Invalid input of event date.</p>";
        $errors += 1;
      }
    }
    // Start time validation.
    if (empty($_POST["event_stime"])) {
      $event_stimeErr = "<p class ='error'>*Please select a start time.</p>";
      $errors += 1;
    } else {
      $event_stime = test_input($_POST["event_stime"]);
    }
    // End time validation.
    if (empty($_POST["event_etime"])) {
      $event_etimeErr = "<p class ='error'>*Please select a end time.</p>";
      $errors += 1;
    } else {
      $event_etime = test_input($_POST["event_etime"]);
      if($event_stime > $event_etime){
        $event_etimeErr = "<p class ='error'>*Invalid time selected.</p>";
        $errors += 1;
      }
    }
    //Image Vailidation
    if (empty($_FILES['image']["tmp_name"])){
      $event_imageErr = "<p class ='error'>*You must choose an image.</p>";
      $errors += 1;
    }else{
      $event_image= addslashes($_FILES['image']['name']);
      if($_FILES['image']["type"] != "image/jpeg" || $_FILES['image']["type"] != "image/jpeg"){
        $event_imageErr = "<p class ='error'>*Image type is incorrect.</p>";
        $errors += 1;
      }
    }
    //SQL queries into database if there is zero errors and create POST Method submit
    if($errors == 0 && isset($_POST["update"])){
      // Move the image to the folder img/event_image.
      move_uploaded_file($_FILES["image"]["tmp_name"],"img/event_image/".$_FILES["image"]["name"]);
      // SQL query to update event.
      $query = "UPDATE tbl_event SET event_name = '$event_name', event_creator = '$event_creator', event_desc = '$event_desc', event_location = '$event_location', event_image = '$event_image',
      event_update = '$event_update', event_date = '$event_date', event_stime = '$event_stime', event_etime = '$event_etime' WHERE event_id = $event_id";
      // If database connection with query is true execute if statement
      if(mysqli_query($db_connect, $query)){
        // Redirect to admin_event if successful
        header("Location:admin_event.php?event_update=success");
      }else{
        //Testing Database connection.
        echo "Unable to update event in the database";
      }
    }
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
      $event_id = test_input($_POST["event_id"]);
    }
    // Event Status validation.
    if (empty($_POST["event_status"])) {
      $event_statusErr = "<p class ='error'>*Please select a event status.</p>";
      $errors += 1;
    } else {
      $event_status = test_input($_POST["event_status"]);
    }
    //SQL queries into database if there is zero errors and create POST Method submit
    if($errors == 0 && isset($_POST["status"])){
      // SQL query to update event status
      $query = "UPDATE tbl_event SET event_status = '$event_status' WHERE event_id = '$event_id'";
      // If database connection with query is true execute if statement
      if(mysqli_query($db_connect, $query)){
        // Redirect to admin_event if successful
        header("Location:admin_event.php?event_status=success");
      }else{
        //Testing Database connection
        echo "Unable to update event status in the database";
      }
    }
  }

  //Delete Event Form validation.
  if (isset($_POST["delete"])) {
    // Number of errors at the beginning of form
    $errors = 0;
    // Event id validation.
    if (empty($_POST["event_id"])) {
      $event_idErr = "<p class ='error'>*Please select event to delete.</p>";
      $errors += 1;
    } else {
      $event_id = test_input($_POST["event_id"]);
    }
    // Delete event validation
    if(empty($_POST['event_confirm'])){
        $event_confirmErr = "<p class ='error'>*Please check the box to confirm delete action.</p>";
        $errors += 1;
    } else {
        $event_confirm = "<p>You have delete a Griffith College Event</p>";
    }
    //SQL queries into database if there is zero errors and create POST Method submit
    if($errors == 0 && isset($_POST["delete"])){
      // Get image from database based on event_id
      $pull_image = "SELECT event_image FROM `tbl_event` WHERE event_id = '$event_id'";
      //Connect to the database with query
      $pull_image_result = mysqli_query($db_connect, $pull_image);
      //Tesr the connection and execute the if statement
      if($pull_image_result){
        //fetch the image from the database
        mysqli_num_rows($pull_image_result);
        $row =mysqli_fetch_array($pull_image_result);
        //Store the image into a variable
        $event_image = $row["event_image"];
        //Created a path towards the image of the event
        $image_path = "img/event_image/".$event_image;
        //Delete the image based on the path created.
        unlink($image_path);
      }
      // SQL query to delete event based on its event_id
      $query = "DELETE FROM `tbl_event` WHERE event_id = '$event_id'";
      // If database connection with query is true execute if statement
      if(mysqli_query($db_connect, $query)){
        // Redirect to admin_event if event delete is successful
        header("Location:admin_event.php?event_delete=success");
      }else{
        //Testing Database connection
        echo "Unable to delete event in the database";
      }
    }
  }
//if there is no errors execute html and php code.
if ($errors == 0) {?>
  <section class="form_container">
    <!--Form for approval of events-->
    <form action="admin_event.php" method="post" class="form">
      <h1>APPROVE EVENTS</h1>
      <label>GRIFFITH COLLEGE EVENTS</label>
      <!--selection of all events in the database table events-->
      <select name="event_id" class="form_control">
        <?php
          $pull_event = "SELECT event_id, event_name FROM tbl_event";
          $pull_event_result = mysqli_query($db_connect, $pull_event);
          if($pull_event_result){
            $num = mysqli_num_rows($pull_event_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_event_result)){
               // Echo out all events option
               echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
             }
           }else{
             // Echo out if there are no events listed.
             echo '<option>No events available</option>';
           }
          }
        ?>
      </select>
      <!--Choosing event status for event-->
      <label>EVENT STATUS*</label>
      <select name="event_status" class="form_control">
        <option value=NULL>WAITING</option>
        <option value="1">APPROVED</option>
        <option value="2">DISAPPROVED</option>
      </select>
      <input type="submit" name="status" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <!--Form for Updating Events-->
    <form action="admin_event.php" method="post" enctype="multipart/form-data" class="form">
      <h1>UPDATE EVENTS</h1>
      <label>GRIFFITH COLLEGE EVENTS</label>
      <!--selection of all events in the database table events-->
      <select name="event_id" class="form_control">
        <?php
          $pull_event = "SELECT event_id, event_name FROM tbl_event";
          $pull_event_result = mysqli_query($db_connect, $pull_event);
          if($pull_event_result){
            $num = mysqli_num_rows($pull_event_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_event_result)){
               // Echo out all events option
               echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
             }
           }else{
             // Echo out if there are no events listed.
             echo '<option>No events available</option>';
           }
          }
        ?>
      </select>
      <label>UPDATE EVENT NAME*</label><input type="text" name="event_name" class="form_control" size="20" maxlength="30">
      <label>UPDATE EVENT ORGANISER NAME*</label><input type="text" name="event_creator" class="form_control">
      <label>UPDATE EVENT DESCRIPTION*</label><textarea rows="5" type="text" name="event_desc" class="form_control" maxlength="1000"></textarea>
      <label>UPDATE EVENT LOCATION*</label><input type="text" name="event_location" maxlength="300" class ="form_control">
      <label>UPDATE EVENT DATE*</label><input type="date" name="event_date" class="form_control">
      <select name="event_stime" class="form_control">
        <option value='10:00:00'>10:00</option>
        <option value='11:00:00'>11:00</option>
        <option value='12:00:00'>12:00</option>
        <option value='13:00:00'>13:00</option>
        <option value='14:00:00'>14:00</option>
        <option value='15:00:00'>15:00</option>
        <option value='16:00:00'>16:00</option>
        <option value='17:00:00'>17:00</option>
        <option value='18:00:00'>18:00</option>
        <option value='19:00:00'>19:00</option>
        <option value='20:00:00'>20:00</option>
        <option value='21:00:00'>21:00</option>
      </select>
      <label>UPDATE EVENT END TIME*</label>
      <select name="event_etime" class="form_control">
        <option value='11:00:00'>11:00</option>
        <option value='12:00:00'>12:00</option>
        <option value='13:00:00'>13:00</option>
        <option value='14:00:00'>14:00</option>
        <option value='15:00:00'>15:00</option>
        <option value='16:00:00'>16:00</option>
        <option value='17:00:00'>17:00</option>
        <option value='18:00:00'>18:00</option>
        <option value='19:00:00'>19:00</option>
        <option value='20:00:00'>20:00</option>
        <option value='21:00:00'>21:00</option>
        <option value='22:00:00'>22:00</option>
        <option value='23:00:00'>23:00</option>
      </select>
      <label>UPDATE EVENT IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
      <input type="submit" name="update" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <!--Form for Deleting Event-->
    <form action="admin_event.php" method="post" class="form">
      <h1>DELETE EVENTS</h1>
      <label>GRIFFITH COLLEGE EVENTS</label>
      <!--selection of all events in the database table events-->
      <select name="event_id" class="form_control">
        <?php
          $pull_event = "SELECT event_id, event_name FROM tbl_event";
          $pull_event_result = mysqli_query($db_connect, $pull_event);
          if($pull_event_result){
            $num = mysqli_num_rows($pull_event_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_event_result)){
               // Echo out events from database
               echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
             }
           }else{
             // Echo out if there are no events listed.
             echo '<option>No events available</option>';
           }
          }
        ?>
      </select>
      <div class="border"></div>
        <!--Vailidation to confirm deleting event-->
        <p><label>I HAVE AGREED TO DELETE THIS EVENT STATED ABOVE.</label>
        <input type="checkbox" name="event_confirm" value="yes" class="form_checkbox"></p>
      <div class="border"></div>
      <input type="submit" name="delete" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); ?>
<!--if there is errors execute html and php code.-->
<?php }else { ?>
  <section class="form_container">
    <!--Form for approval of events-->
    <form action="admin_event.php" method="post" class="form">
      <h1>EVENTS APPROVAL</h1>
      <label>GRIFFITH COLLEGE EVENTS</label>
      <!--selection of all events in the database table events-->
      <select name="event_id" class="form_control">
        <?php
          $pull_event = "SELECT event_id, event_name FROM tbl_event";
          $pull_event_result = mysqli_query($db_connect, $pull_event);
          if($pull_event_result){
            $num = mysqli_num_rows($pull_event_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_event_result)){
               // Echo out all events option
               echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
             }
           }else{
             // Echo out if there are no events listed.
             echo '<option>No events available</option>';
           }
          }
        ?>
      </select>
      <!--Choosing event status for event-->
      <label>EVENT STATUS*</label>
      <select name="event_status" class="form_control">
        <option value=NULL>WAITING</option>
        <option value="1">APPROVED</option>
        <option value="2">DISAPPROVED</option>
      </select>
      <input type="submit" name="status" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <!--Form for Updating Events-->
    <form action="admin_event.php" method="post" enctype="multipart/form-data" class="form">
      <h1>UPDATE EVENTS</h1>
      <label>GRIFFITH COLLEGE EVENTS</label>
      <!--selection of all events in the database table events-->
      <select name="event_id" class="form_control">
        <?php
          $pull_event = "SELECT event_id, event_name FROM tbl_event";
          $pull_event_result = mysqli_query($db_connect, $pull_event);
          if($pull_event_result){
            $num = mysqli_num_rows($pull_event_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_event_result)){
               // Echo out all events option
               echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
             }
           }else{
             // Echo out if there are no events listed.
             echo '<option>No events available</option>';
           }
          }
        ?>
      </select>
      <?php echo $event_idErr; ?>
      <label>UPDATE EVENT NAME*</label><input type="text" name="event_name" class="form_control" size="20" maxlength="30">
      <?php echo $event_nameErr; ?>
      <label>UPDATE EVENT ORGANISER NAME*</label><input type="text" name="event_creator" class="form_control">
      <?php echo $event_creatorErr; ?>
      <label>UPDATE EVENT DESCRIPTION*</label><textarea rows="5" type="text" name="event_desc" class="form_control" maxlength="1000"></textarea>
      <?php echo $event_descErr; ?>
      <label>UPDATE EVENT LOCATION*</label><input type="text" name="event_location" maxlength="300" class ="form_control">
      <?php echo $event_locationErr; ?>
      <label>UPDATE EVENT DATE*</label><input type="date" name="event_date" class="form_control">
      <?php echo $event_dateErr; ?>
      <label>UPDATE EVENT START TIME*</label>
      <select name="event_stime" class="form_control">
        <option value='10:00:00'>10:00</option>
        <option value='11:00:00'>11:00</option>
        <option value='12:00:00'>12:00</option>
        <option value='13:00:00'>13:00</option>
        <option value='14:00:00'>14:00</option>
        <option value='15:00:00'>15:00</option>
        <option value='16:00:00'>16:00</option>
        <option value='17:00:00'>17:00</option>
        <option value='18:00:00'>18:00</option>
        <option value='19:00:00'>19:00</option>
        <option value='20:00:00'>20:00</option>
        <option value='21:00:00'>21:00</option>
      </select>
      <?php echo $event_stimeErr; ?>
      <label>UPDATE EVENT END TIME*</label>
      <select name="event_etime" class="form_control">
        <option value='11:00:00'>11:00</option>
        <option value='12:00:00'>12:00</option>
        <option value='13:00:00'>13:00</option>
        <option value='14:00:00'>14:00</option>
        <option value='15:00:00'>15:00</option>
        <option value='16:00:00'>16:00</option>
        <option value='17:00:00'>17:00</option>
        <option value='18:00:00'>18:00</option>
        <option value='19:00:00'>19:00</option>
        <option value='20:00:00'>20:00</option>
        <option value='21:00:00'>21:00</option>
        <option value='22:00:00'>22:00</option>
        <option value='23:00:00'>23:00</option>
      </select>
      <?php echo $event_etimeErr; ?>
      <label>UPDATE EVENT IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
      <?php echo $event_imageErr; ?>
      <input type="submit" name="update" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <form action="admin_event.php" method="post" class="form">
      <!--Form for Deleting Event-->
      <h1>DELETE EVENTS</h1>
      <label>GRIFFITH COLLEGE EVENTS</label>
      <!--selection of all events in the database table events-->
      <select name="event_id" class="form_control">
        <?php
          $pull_event = "SELECT event_id, event_name FROM tbl_event";
          $pull_event_result = mysqli_query($db_connect, $pull_event);
          if($pull_event_result){
            $num = mysqli_num_rows($pull_event_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_event_result)){
               // Echo out events from database
               echo '<option value="'.$row["event_id"].'">'.$row["event_name"].'</option>';
             }
           }else{
             // Echo out if there are no events listed.
             echo '<option>No events available</option>';
           }
          }
        ?>
      </select>
      <div class="border"></div>
        <p><label>I HAVE AGREED TO DELETE THIS EVENT STATED ABOVE.</label>
        <input type="checkbox" name="event_confirm" value="yes" class="form_checkbox"></p>
        <?php echo $event_confirmErr; ?>
      <div class="border"></div>
      <input type="submit" name="delete" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); }?>
