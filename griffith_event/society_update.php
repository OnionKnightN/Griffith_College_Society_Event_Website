<!--Header which contains the connection to the database and navigation bar-->
<?php include_once("header.php"); ?>
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
  $pull_society = "SELECT soc_name FROM tbl_soc WHERE soc_president= $user_id";
  // Storing the connection in a variable
  $pull_society_result = mysqli_query($db_connect, $pull_society);
  // Testing and executing connection
  if($pull_society_result){
     mysqli_num_rows($pull_society_result);
     $row =mysqli_fetch_array($pull_society_result);
     // Creating varibables to store information on event_soc and soc_name
     $soc_name = strtoupper($row["soc_name"]);
  }else{
    // Echo out if there are no society name listed.
    echo 'No Society Available';
  }
  // Declaring global variables
  // Input of information of Society Form
  $soc_image = "";
  $soc_desc = "";
  $soc_day = "";
  $soc_stime = "";
  $soc_etime = "";
  $soc_phone = "";
  // Error Messages of Society Form
  $soc_imageErr = "";
  $soc_descErr = "";
  $soc_dayErr = "";
  $soc_stimeErr = "";
  $soc_etimeErr = "";
  $soc_phoneErr = "";
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
  // Create Society Form validation.
  if (isset($_POST["update"])) {
    // Number of errors at the beginning of form
    $errors = 0;
    // Society Description validation.
    if (empty($_POST["soc_desc"])) {
      $soc_descErr = "<p class ='error'>*Society description is required.</p>";
      $errors += 1;
    }else{
      $soc_desc = test_input($_POST["soc_desc"]);
      if (!preg_match("/^[a-zA-Z0-9 ]/", $soc_desc)) {
        $soc_descErr = "<p class ='error'>*Invalid society description.</p>";
        $errors += 1;
      }
    }
    // Meet up day validation.
    if (empty($_POST["soc_day"])) {
      $soc_dayErr = "<p class ='error'>*Please select a meet up day.</p>";
      $errors += 1;
    } else {
      $soc_day = test_input($_POST["soc_day"]);
    }
    // Start time validation.
    if (empty($_POST["soc_stime"])) {
      $soc_stimeErr = "<p class ='error'>*Please select a start time.</p>";
      $errors += 1;
    } else {
      $soc_stime = test_input($_POST["soc_stime"]);
    }
    // End time validation.
    if (empty($_POST["soc_etime"])) {
      $soc_etimeErr = "<p class ='error'>*Please select a end time.</p>";
      $errors += 1;
    } else {
      $soc_etime = test_input($_POST["soc_etime"]);
      if($soc_stime > $soc_etime){
        $soc_etimeErr = "<p class ='error'>*Invalid time selected.</p>";
        $errors += 1;
      }
    }
    // Phone validation.
    if (empty($_POST["soc_phone"])) {
      $soc_phoneErr = "<p class ='error'>*Phone number is required.</p>";
      $errors += 1;
    }else {
      $soc_phone = test_input($_POST["soc_phone"]);
      if (!preg_match("/^08[3-9]{1}[0-9]{7}$/", $soc_phone)){
        $soc_phoneErr = "<p class ='error'>*Invalid phone number.</p>";
        $errors += 1;
      }
    }
    //Image Vailidation
    if (empty($_FILES['image']["tmp_name"])){
      $soc_imageErr = "<p class ='error'>*You must choose an image.</p>";
      $errors += 1;
    }else{
      $soc_image= addslashes($_FILES['image']['name']);
      if($_FILES['image']["type"] != "image/jpeg" || $_FILES['image']["type"] != "image/jpeg"){
        $soc_imageErr = "<p class ='error'>*Image type is incorrect.</p>";
        $errors += 1;
      }
    }
    //SQL queries into database if there is zero errors and create post submit
    if($errors == 0 && isset($_POST["update"])){
      // Move the image to the folder img/society_image.
      move_uploaded_file($_FILES["image"]["tmp_name"],"img/society_image/".$_FILES["image"]["name"]);
      // SQL query to Update society information.
      $query = "UPDATE tbl_soc SET soc_desc = '$soc_desc', soc_day = '$soc_day', soc_stime = '$soc_stime', soc_etime = '$soc_etime', soc_phone = '$soc_phone',soc_image = '$soc_image' WHERE soc_president = $user_id";
      if(mysqli_query($db_connect, $query)){
        //If successful redirect to society_update.php?update_society=success
        header("Location:society_update.php?update_society=success");
      }else{
        //Testing Database connection
        echo "Unable to update a society into the database";
      }
    }
  }
  //If there is zero error present the folling form
  if ($errors == 0) {?>
  <section class="form_container">
    <form action="society_update.php" enctype="multipart/form-data" method="post" class="form">
      <h1>UPDATE <?php echo $soc_name;?> SOCIETY</h1>
      <label>SOCIETY DESCRIPTION*</label><textarea rows="5" type="text" name="soc_desc" class="form_control" maxlength="1000"></textarea>
      <label>SOCIETY MEET UP DAY*</label>
      <select name="soc_day" class="form_control">
        <option value="Monday">MONDAY</option>
        <option value="Tuesday">TUESDAY</option>
        <option value="Wednesday">WEDNESDAY</option>
        <option value="Thursday">THURSDAY</option>
        <option value="Friday">FRIDAY</option>
        <option value="Saturday">SATURDAY</option>
        <option value="Sunday">SUNDAY</option>
      </select>
      <label>SOCIETY START TIME*</label>
      <select name="soc_stime" class="form_control">
        <option value='17:00:00'>17:00</option>
        <option value='18:00:00'>18:00</option>
        <option value='19:00:00'>19:00</option>
        <option value='20:00:00'>20:00</option>
        <option value='21:00:00'>21:00</option>
        <option value='22:00:00'>22:00</option>
      </select>
      <label>SOCIETY END TIME*</label>
      <select name="soc_etime" class="form_control">
        <option value='18:00:00'>18:00</option>
        <option value='19:00:00'>19:00</option>
        <option value='20:00:00'>20:00</option>
        <option value='21:00:00'>21:00</option>
        <option value='22:00:00'>22:00</option>
        <option value='23:00:00'>23:00</option>
      </select>
      <label>PHONE NUMBER</label><input type="tel" name="soc_phone" placeholder="eg.0861238200" class="form_control"/>
      <label>SOCIETY IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
      <input type="submit" name="update" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <!--Footer which contains the disconnection to the database and footer section-->
  <?php include_once("footer.php"); ?>
  <!--If there is an error present the following form with error varibles-->
  <?php }else { ?>
    <section class="form_container">
      <form action="society_update.php" method="post" enctype="multipart/form-data" class="form">
        <h1>UPDATE <?php echo $soc_name;?> SOCIETY</h1>
        <label>SOCIETY DESCRIPTION*</label><textarea rows="5" type="text" name="soc_desc" class="form_control" maxlength="1000"></textarea>
        <?php echo $soc_descErr; ?>
        <label>SOCIETY MEET UP DAY*</label>
        <select name="soc_day" class="form_control">
          <option value="Monday">MONDAY</option>
          <option value="Tuesday">TUESDAY</option>
          <option value="Wednesday">WEDNESDAY</option>
          <option value="Thursday">THURSDAY</option>
          <option value="Friday">FRIDAY</option>
          <option value="Saturday">SATURDAY</option>
          <option value="Sunday">SUNDAY</option>
        </select>
        <label>SOCIETY START TIME*</label>
        <select name="soc_stime" class="form_control">
          <option value='17:00:00'>17:00</option>
          <option value='18:00:00'>18:00</option>
          <option value='19:00:00'>19:00</option>
          <option value='20:00:00'>20:00</option>
          <option value='21:00:00'>21:00</option>
          <option value='22:00:00'>22:00</option>
        </select>
        <label>SOCIETY END TIME*</label>
        <select name="soc_etime" class="form_control">
          <option value='18:00:00'>18:00</option>
          <option value='19:00:00'>19:00</option>
          <option value='20:00:00'>20:00</option>
          <option value='21:00:00'>21:00</option>
          <option value='22:00:00'>22:00</option>
          <option value='23:00:00'>23:00</option>
        </select>
        <?php $soc_etimeErr ?>
        <label>PHONE NUMBER</label><input type="tel" name="soc_phone" placeholder="eg.0861238200" class="form_control"/>
        <?php echo $soc_phoneErr; ?>
        <label>SOCIETY IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
        <?php echo $soc_imageErr; ?>
        <input type="submit" name="update" value="SUBMIT" class="formBtn" required>
      </form>
    </section>
  <!--Footer which contains the disconnection to the database and footer section-->
  <?php include_once("footer.php"); }?>
