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
  // Input of information of Society Form
  $soc_id = "";
  $soc_name = "";
  $soc_president = "";
  $soc_image = "";
  $soc_desc = "";
  $soc_day = "";
  $soc_stime = "";
  $soc_etime = "";
  $soc_email = "";
  $soc_phone = "";
  $soc_status = "1";
  // Error Messages of Society Form
  $socErr_id = "";
  $soc_nameErr = "";
  $soc_presidentErr = "";
  $soc_presidentErr2 = "";
  $soc_imageErr = "";
  $soc_descErr = "";
  $soc_dayErr = "";
  $soc_stimeErr = "";
  $soc_etimeErr = "";
  $soc_emailErr = "";
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
  //Create Society Form validation.
  if (isset($_POST["create"])) {
    //Number of errors at the beginning of form
    $errors = 0;
    //Society Name validation.
    if (empty($_POST["soc_name"])) {
      $soc_nameErr = "<p class ='error'>*Society name is required.</p>";
      $errors += 1;
    }else{
      $soc_name = test_input($_POST["soc_name"]);
      if (!preg_match("/^[a-zA-Z ]/", $soc_name)) {
        $soc_nameErr = "<p class ='error'>*Invalid society name.</p>";
        $errors += 1;
      }
    }
    //Society President validation.
    if (empty($_POST["soc_president"])) {
      $soc_presidentErr = "<p class ='error'>*Please provide student id.</p>";
      $errors += 1;
    }else {
      $soc_president = test_input($_POST["soc_president"]);
      if (!preg_match("/^[0-9]{7}$/", $soc_president)) {
        $soc_presidentErr = "<p class ='error'>*Please provide vaild student id.</p>";
        $errors += 1;
      }
    }
    //Society Description validation.
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
    //Meet up day validation.
    if (empty($_POST["soc_day"])) {
      $soc_dayErr = "<p class ='error'>*Please select a meet up day.</p>";
      $errors += 1;
    } else {
      $soc_day = test_input($_POST["soc_day"]);
    }
    //Start time validation.
    if (empty($_POST["soc_stime"])) {
      $soc_stimeErr = "<p class ='error'>*Please select a start time.</p>";
      $errors += 1;
    } else {
      $soc_stime = test_input($_POST["soc_stime"]);
    }
    //End time validation.
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
    //Email validation.
    if (empty($_POST['soc_email'])) {
      $soc_emailErr = "<p class ='error'>*Email is required.</p>";
      $errors += 1;
    }elseif (($_POST['soc_email']) != ($_POST['confirm_soc_email'])){
      $soc_emailErr = "<p class ='error'>*Emails are not matching.</p>";
      $errors += 1;
    }else{
      $soc_email = test_input($_POST['soc_email']);
      $soc_email = filter_var($soc_email, FILTER_SANITIZE_EMAIL);
      if (!preg_match("/@society.griffith.ie/", $soc_email)){
        $soc_emailErr = "<p class ='error'>*Invalid email address.</p>";
        $errors += 1;
      }
    }
    //Phone validation.
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
    //SQL queries into database if there is zero errors and create POST Method submit
    if($errors == 0 && isset($_POST["create"])){
      // Move the image to the folder img/society_image.
      move_uploaded_file($_FILES["image"]["tmp_name"],"img/society_image/".$_FILES["image"]["name"]);
      // SQL query to insert new society.
      $query = "INSERT INTO tbl_soc(soc_president, soc_name, soc_image, soc_desc, soc_day, soc_stime, soc_etime, soc_email, soc_phone, soc_status)
      VALUES('$soc_president','$soc_name','$soc_image','$soc_desc','$soc_day','$soc_stime','$soc_etime','$soc_email','$soc_phone','$soc_status')";
      // If database connection with query is true execute if statement
      if(mysqli_query($db_connect, $query)){
        // Change usertype to Society if new society is created
        $update_user_member= "UPDATE tbl_user SET user_type ='Society' WHERE user_id = $soc_president";
        mysqli_query($db_connect, $update_user_member);
        // Redirect to admin_society if successful
        header("Location:admin_society.php?new_society=success");
      }else{
        //Testing Database connection
        echo "Unable to inserted a society into the database";
      }
    }
  }

  // Change Society Status Form validation.
  if (isset($_POST["status"])) {
    // Number of errors at the beginning of form
    $errors = 0;
    // Society ID validation.
    if (empty($_POST["soc_id"])) {
      $soc_idErr = "<p class ='error'>*Please select a Society.</p>";
      $errors += 1;
    } else {
      $soc_id = test_input($_POST["soc_id"]);
    }
    // Society Status validation.
    if (empty($_POST["soc_status"])) {
      $soc_statusErr = "<p class ='error'>*Please select Society Status.</p>";
      $errors += 1;
    } else {
      $soc_status = test_input($_POST["soc_status"]);
    }
  }
  //SQL queries into database if there is zero errors and create POST Method submit
  if($errors == 0 && isset($_POST["status"])){
    // SQL query to update society.
    $query = "UPDATE tbl_soc SET soc_status = $soc_status WHERE soc_id = $soc_id";
    if(mysqli_query($db_connect, $query)){
      // Fetch society president based on society
      $pull_president= "SELECT soc_president FROM tbl_soc WHERE soc_id = $soc_id";
      $pull_president_result = mysqli_query($db_connect, $pull_president);
      // If database connection with query is true execute if statement
      if($pull_president_result){
        // Fetch information from database
        mysqli_num_rows($pull_president_result);
        $row =mysqli_fetch_array($pull_president_result);
        // Creating a variable for soc president based on society
        $president_id = $row["soc_president"];
        //If the society is active
        if($soc_status == 1){
          // Update/change current society president to Society
          $update_user_president= "UPDATE tbl_user SET user_type ='Society' WHERE user_id = $president_id";
          mysqli_query($db_connect, $update_user_president);
        }else{
          // Update/change current society president to member
          $change_president = "UPDATE tbl_user SET user_type ='Member' WHERE user_id = $president_id";
          mysqli_query($db_connect, $change_president);
        }
      }
      // Redirect to admin_society if successful
      header("Location:admin_society.php?new_society_status=success");
    }else{
      //Testing Database connection
      echo "Unable to change society status in database";
    }
  }

  // Change Society President Form validation.
  if (isset($_POST["president"])) {
    // Number of errors at the beginning of form
    $errors = 0;
    // Society ID validation.
    if (empty($_POST["soc_id"])) {
      $soc_idErr = "<p class ='error'>*Please select a Society.</p>";
      $errors += 1;
    } else {
      $soc_id = test_input($_POST["soc_id"]);
    }
    // Society President validation.
    if (empty($_POST["soc_president"])) {
      $soc_presidentErr2 = "<p class ='error'>*Please provide student id.</p>";
      $errors += 1;
    }else {
      $soc_president = test_input($_POST["soc_president"]);
      if (!preg_match("/^[0-9]{7}$/", $soc_president)) {
        $soc_presidentErr2 = "<p class ='error'>*Please provide vaild student id.</p>";
        $errors += 1;
      }
    }
  }
  //SQL queries into database if there is zero errors and create POST Method submit
  if($errors == 0 && isset($_POST["president"])){
    // Fetch society president based on society
    $pull_president= "SELECT soc_president FROM tbl_soc WHERE soc_id = $soc_id";
    $pull_president_result = mysqli_query($db_connect, $pull_president);
    // If database connection with query is true execute if statement
    if($pull_president_result){
      // Fetch information from database
      mysqli_num_rows($pull_president_result);
      $row =mysqli_fetch_array($pull_president_result);
      // Creating a variable for soc president based on society
      $president_id = $row["soc_president"];
      // Update/change current society president to member
      $update_user_president= "UPDATE tbl_user SET user_type ='Member' WHERE user_id = $president_id";
      if(mysqli_query($db_connect, $update_user_president)){
        // Update/change member to Society Type
        $update_user_member= "UPDATE tbl_user SET user_type ='Society' WHERE user_id = $soc_president";
        if(mysqli_query($db_connect, $update_user_member)){
        // Update/change member to Society President
        $change_president = "UPDATE tbl_soc SET soc_president = $soc_president WHERE soc_id = $soc_id";
          if(mysqli_query($db_connect, $change_president)){
            //redirect admin_society if successful.
            header("Location:admin_society.php?new_society_president=success");
          }
        }
      }
    }else{
      //Testing Database connection
      header("Location:admin_society.php?new_society_president=unsuccess");
      //error message if invaild student id
      $soc_presidentErr2 = "<p class ='error'>*Please provide vaild student id</p>";
    }
  }
//if there is no errors execute html and php code.
if ($errors == 0) {?>
  <section class="form_container">
    <!--Form for creating new society-->
    <form action="admin_society.php" method="post" enctype="multipart/form-data" class="form">
      <h1>CREATE NEW SOCIETY</h1>
      <label>SOCIETY NAME*</label><input type="text" name="soc_name" class="form_control" size="20" maxlength="30">
      <label>SOCIETY PRESIDENT*</label><input type="text" name="soc_president" class="form_control" placeholder="eg.2989969" >
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
      <label>EMAIL ADDRESS*</label>
      <input type="email" name="soc_email" placeholder = "@society.griffith.ie" class="form_control"/>
      <label>CONFIRM EMAIL*</label>
      <input type="email" name="confirm_soc_email" placeholder = "@society.griffith.ie" class="form_control"/>
      <label>SOCIETY IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
      <input type="submit" name="create" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <!--Form for changing society status-->
    <form action="admin_society.php" method="post" class="form">
      <h1>CHANGE SOCIETY STATUS</h1>
      <label>SOCIETY NAME*</label>
      <!--selection of all societies in the database table events-->
      <select name="soc_id" class="form_control">
        <?php
          $pull_society = "SELECT soc_id, soc_name FROM tbl_soc";
          $pull_society_result = mysqli_query($db_connect, $pull_society);
          if($pull_society_result){
            $num = mysqli_num_rows($pull_society_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_society_result)){
               // Echo out all society option
               echo '<option value="'.$row["soc_id"].'">'.$row["soc_name"].'</option>';
             }
           }else{
             // Echo out if there are no society listed.
             echo '<option>No Society available</option>';
           }
          }
        ?>
      </select>
      <label>SOCIETY STATUS*</label>
      <select name="soc_status" class="form_control">
        <option value = '1'>Active</option>
        <option value = NULL>Inactive</option>
      </select>
      <input type="submit" name="status" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <!--Form for changing society president-->
    <form action="admin_society.php" method="post" class="form">
      <h1>CHANGE SOCIETY PRESIDENT</h1>
      <label>SOCIETY NAME*</label>
      <!--selection of all societies in the database table events-->
      <select name="soc_id" class="form_control">
        <?php
          $pull_society = "SELECT soc_id, soc_name FROM tbl_soc";
          $pull_society_result = mysqli_query($db_connect, $pull_society);
          if($pull_society_result){
            $num = mysqli_num_rows($pull_society_result);
           // Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_society_result)){
               // Echo out all society option
               echo '<option value="'.$row["soc_id"].'">'.$row["soc_name"].'</option>';
             }
           }else{
             // Echo out if there are no society listed.
             echo '<option>No Society Available</option>';
           }
          }
        ?>
      </select>
      <label>SOCIETY PRESIDENT*</label>
      <input type="text" name="soc_president" class="form_control" placeholder="eg.2989969" >
      <input type="submit" name="president" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); ?>
<!--if there is errors execute html and php code.-->
<?php }else { ?>
  <section class="form_container">
    <!--Form for creating new society-->
    <form action="admin_society.php" method="post" enctype="multipart/form-data" class="form">
      <h1>CREATE NEW SOCIETY</h1>
      <label>SOCIETY NAME*</label><input type="text" name="soc_name" class="form_control" size="20" maxlength="30">
      <?php echo $soc_nameErr; ?>
      <label>SOCIETY PRESIDENT*</label><input type="text" name="soc_president" class="form_control" placeholder="eg.2989969" >
      <?php echo $soc_presidentErr; ?>
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
      <?php echo $soc_etimeErr; ?>
      <label>PHONE NUMBER</label><input type="tel" name="soc_phone" placeholder="eg.0861238200" class="form_control"/>
      <?php echo $soc_phoneErr; ?>
      <label>EMAIL ADDRESS*</label>
      <input type="email" name="soc_email" placeholder = "@society.griffith.ie" class="form_control"/>
      <label>CONFIRM EMAIL*</label>
      <input type="email" name="confirm_soc_email" placeholder = "@society.griffith.ie" class="form_control"/>
      <?php echo $soc_emailErr; ?>
      <label>SOCIETY IMAGE</label><input type="file" class="form_control" accept="image/png, image/jpeg" name="image">
      <?php echo $soc_imageErr; ?>
      <input type="submit" name="create" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <!--Form for changing society status-->
    <form action="admin_society.php" method="post" class="form">
      <h1>CHANGE SOCIETY STATUS</h1>
      <label>SOCIETY NAME*</label>
      <!--selection of all societies in the database table events-->
      <select name="soc_id" class="form_control">
        <?php
          $pull_society = "SELECT soc_id, soc_name FROM tbl_soc";
          $pull_society_result = mysqli_query($db_connect, $pull_society);
          if($pull_society_result){
            $num = mysqli_num_rows($pull_society_result);
           //  Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_society_result)){
               // Echo out all society option
               echo '<option value="'.$row["soc_id"].'">'.$row["soc_name"].'</option>';
             }
           }else{
             // Echo out if there are no society listed.
             echo '<option>No Society Available</option>';
           }
          }
        ?>
      </select>
      <label>SOCIETY STATUS*</label>
      <select name="soc_status" class="form_control">
        <option value = '1'>Active</option>
        <option value = 'NULL'>Inactive</option>
      </select>
      <input type="submit" name="status" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
  <section class="form_container">
    <!--Form for changing society president-->
    <form action="admin_society.php" method="post" class="form">
      <h1>CHANGE SOCIETY PRESIDENT</h1>
      <label>SOCIETY NAME*</label>
      <!--selection of all societies in the database table events-->
      <select name="soc_id" class="form_control">
        <?php
          $pull_society = "SELECT soc_id, soc_name FROM tbl_soc";
          $pull_society_result = mysqli_query($db_connect, $pull_society);
          if($pull_society_result){
            $num = mysqli_num_rows($pull_society_result);
           // Test for valid test result
           if($num>0){
             while($row =mysqli_fetch_array($pull_society_result)){
               // Echo out all society option
               echo '<option value="'.$row["soc_id"].'">'.$row["soc_name"].'</option>';
             }
           }else{
             // Echo out if there are no society listed.
             echo '<option>No Society Available</option>';
           }
          }
        ?>
      </select>
      <label>SOCIETY PRESIDENT*</label>
      <input type="text" name="soc_president" class="form_control" placeholder="eg.2989969" >
      <?php echo $soc_presidentErr2; ?>
      <input type="submit" name="president" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); }?>
