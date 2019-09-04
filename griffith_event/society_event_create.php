<!--Header which contains the connection to the database and navigation bar-->
<?php require_once("header.php"); ?>
<?php
  // Validate for logged in users as Society.
  if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
    if($_SESSION["user_type"] == "Society"){
      $user_id = $_SESSION['user_id'];
    }else{
      //You not logged in as a Society User
      header("Location: index.php?error=forbidden");
    }
  }else{
    //You have not logged into the website
    header("Location: login.php?error=forbidden");
  }
?>
<?php
  // Fetch society based society president
  $pull_society = "SELECT soc_id, soc_name FROM tbl_soc WHERE soc_president= $user_id;";
  // Storing the connection in a variable
  $pull_society_result = mysqli_query($db_connect, $pull_society);
  // Testing and executing connection
  if($pull_society_result){
     mysqli_num_rows($pull_society_result);
     $row =mysqli_fetch_array($pull_society_result);
     // Creating varibables to store information on event_soc and soc_name
     $event_soc = $row["soc_id"];
     $soc_name = strtoupper($row["soc_name"]);
  }else{
    // Echo out if there are no society name listed.
    echo 'No Society Available';
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
    $event_status = "0";
    // Error Messages of Event Form
    $event_idErr = "";
    $event_nameErr = "";
    $event_socErr = "";
    $event_creatorErr = "";
    $event_descErr = "";
    $event_locationErr = "";
    $event_imageErr = "";
    $event_dateErr = "";
    $event_stimeErr = "";
    $event_etimeErr = "";
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

    // Create Event Form validation.
    if (isset($_POST["create"])) {
      // Number of errors at the beginning of form
      $errors = 0;
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
      //SQL queries into database and image upload to folder if there is zero errors with the post submit
      if($errors == 0 && isset($_POST["create"])){
        // Move the image to the folder img/event_image.
        move_uploaded_file($_FILES["image"]["tmp_name"],"img/event_image/".$_FILES["image"]["name"]);
        // SQL query to insert new event.
        $query = "INSERT INTO tbl_event(event_name, event_soc, event_creator, event_desc, event_location, event_image, event_update, event_date, event_stime, event_etime, event_status)
        VALUES('$event_name','$event_soc','$event_creator','$event_desc','$event_location','$event_image','$event_update','$event_date','$event_stime','$event_etime','$event_status')";
        if(mysqli_query($db_connect, $query)){
          //If successful redirect to society_event_create.php?event_create=success
          header("Location:society_event_create.php?event_create=success");
        }else{
          //Testing Database connection
          echo "Unable to inserted event into the database";
        }
      }
    }
    // if there is zero errors execute the following form
    if ($errors == 0) {?>
    <section class="form_container">
      <form action="society_event_create.php" method="post" enctype="multipart/form-data" class="form">
        <h1>CREATE NEW <?php echo $soc_name?> EVENT</h1>
        <label>EVENT NAME*</label><input type="text" name="event_name" class="form_control" size="20" maxlength="30">
        <label>EVENT ORGANISER NAME*</label><input type="text" name="event_creator" class="form_control">
        <label>EVENT DESCRIPTION*</label><textarea rows="5" type="text" name="event_desc" class="form_control" maxlength="1000"></textarea>
        <label>EVENT LOCATION*</label><input type="text" name="event_location" maxlength="300" class ="form_control">
        <label>EVENT DATE*</label><input type="date" name="event_date" class="form_control">
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
        <label>EVENT END TIME*</label>
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
        <label>EVENT IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
        <input type="submit" name="create" value="SUBMIT" class="formBtn" required>
      </form>
    </section>
    <!--Footer which contains the disconnection to the database and footer section-->
    <?php include_once("footer.php");?>
    <!--If there is an error then present the following form with error variables-->
    <?php }else { ?>
      <section class="form_container">
        <form action="society_event_create.php" method="post" enctype="multipart/form-data" class="form">
          <h1>CREATE NEW <?php echo $soc_name?> EVENT</h1>
          <label>EVENT NAME*</label><input type="text" name="event_name" class="form_control" size="20" maxlength="30">
          <?php echo $event_nameErr; ?>
          <label>EVENT ORGANISER NAME*</label><input type="text" name="event_creator" class="form_control">
          <?php echo $event_creatorErr; ?>
          <label>EVENT DESCRIPTION*</label><textarea rows="5" type="text" name="event_desc" class="form_control" maxlength="1000"></textarea>
          <?php echo $event_descErr; ?>
          <label>EVENT LOCATION*</label><input type="text" name="event_location" maxlength="300" class ="form_control">
          <?php echo $event_locationErr; ?>
          <label>EVENT DATE*</label><input type="date" name="event_date" class="form_control">
          <?php echo $event_dateErr; ?>
          <label>EVENT START TIME*</label>
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
          <label>EVENT END TIME*</label>
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
          <label>EVENT IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
          <?php echo $event_imageErr; ?>
          <input type="submit" name="create" value="SUBMIT" class="formBtn" required>
        </form>
      </section>
    <!--Footer which contains the disconnection to the database and footer section-->
    <?php include_once("footer.php"); }?>
  </body>
</html>
