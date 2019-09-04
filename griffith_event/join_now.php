<!--Header which contains the connection to the database and navigation bar-->
<?php include_once("header.php"); ?>
<?php
  // Validate for logged in users, if user is logged in redirect to index page
  if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
    header("Location: index.php?error=forbidden");
  }
?>
<?php
  // Declaring global variables
  // Input of information of Forms
  $user_type = "Member";
  $user_id= "";
  $user_title = "";
  $user_fname = "";
  $user_lname = "";
  $user_password = "";
  $user_address = "";
  $user_email = "";
  $user_phone = "";
  $user_gender = "";
  $user_dob = "";
  $user_terms = "";
  // Error Messages of Forms
  $user_idErr= "";
  $user_titleErr = "";
  $user_fnameErr = "";
  $user_lnameErr = "";
  $user_passwordErr = "";
  $user_addressErr = "";
  $user_emailErr = "";
  $user_phoneErr = "";
  $user_genderErr = "";
  $user_dobErr = "";
  $user_termsErr = "";
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
  // Form validation for join_now page.
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Number of errors at the beginning of form
    $errors = 0;
    // Student Number validation.
    if (empty($_POST["user_id"])) {
      $user_idErr = "<p class ='error'>*Please provide a student id.</p>";
      $errors += 1;
    }else {
      $user_id = test_input($_POST["user_id"]);
      if (!preg_match("/^[0-9]{7}$/", $user_id)) {
        $user_idErr = "<p class ='error'>*Please provide vaild student id.</p>";
        $errors += 1;
      }
    }
    // Title validation.
    if (empty($_POST["user_title"])) {
      $user_titleErr = "<p class ='error'>*Please select a title.</p>";
      $errors += 1;
    }else {
      $user_title  = test_input($_POST["user_title"]);
    }
    // First Name validation.
    if (empty($_POST["user_fname"])) {
      $user_fnameErr = "<p class ='error'>*First name is required.</p>";
      $errors += 1;
    } else {
      $user_fname = test_input($_POST["user_fname"]);
      if (!preg_match("/^[a-zA-Z]/", $user_fname)) {
        $user_fnameErr = "<p class ='error'>*Invalid Firstname(use only letters and white space)</p>";
        $errors += 1;
      }
    }
    // Last Name validation.
    if (empty($_POST["user_lname"])) {
      $user_lnameErr = "<p class ='error'>*Last name is required.</p>";
      $errors += 1;
    } else {
      $user_lname = test_input($_POST["user_lname"]);
      if (!preg_match("/^[a-zA-Z]/", $user_lname)) {
        $user_lnameErr = "<p class ='error'>*Invalid Lastname(use only letters and white space).</p>";
        $errors += 1;
      }
    }
    //Password validation
    if (empty($_POST['user_password']) && empty($_POST['user_password2'])) {
      $user_wordErr = "<p class ='error'>*The passwords are required.</p>";
      $errors += 1;
    } elseif (($_POST['user_password']) != ($_POST['user_password2'])) {
      $user_passwordErr = "<p class ='error'>*The passwords do not match.</p>";
    } else {
      $user_password = test_input($_POST['user_password']);
      $user_password = password_hash($user_password, PASSWORD_DEFAULT);
    }
    // Address validation.
    if (empty($_POST["user_address"])) {
      $user_addressErr = "<p class ='error'>*Address is required.</p>";
      $errors += 1;
    } else {
      $user_address = test_input($_POST["user_address"]);
      if (!preg_match("/[1-9]+[a-zA-Z1-9 ]+/", $user_address)) {
        $user_addressErr = "<p class ='error'>*Invalid address.</p>";
        $errors += 1;
      }
    }
    // Email validation.
    if (empty($_POST['user_email'])) {
      $user_emailErr = "<p class ='error'>*Email is required.</p>";
      $errors += 1;
    } elseif (($_POST['user_email']) != ($_POST['confirm_user_email'])) {
      $user_emailErr = "<p class ='error'>*Emails are not matching.</p>";
      $errors += 1;
    } else {
      $user_email = test_input($_POST['user_email']);
      $user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
      if (!preg_match("/@student.griffith.ie/", $user_email)) {
        $user_emailErr = "<p class ='error'>*Invalid email address.</p>";
        $errors += 1;
      }
    }
    // Phone validation.
    if (empty($_POST["user_phone"])) {
      $user_phoneErr = "<p class ='error'>*Phone number is required.</p>";
      $errors += 1;
    } else {
      $user_phone = test_input($_POST["user_phone"]);
      if (!preg_match("/^08[3-9]{1}[0-9]{7}$/", $user_phone)){
        $user_phoneErr = "<p class ='error'>*Invalid phone number.</p>";
        $errors += 1;
      }
    }
    // Gender validation.
    if (empty($_POST["user_gender"])) {
      $user_genderErr = "<p class ='error'>*Please select a gender type.</p>";
      $errors += 1;
    } else {
      $user_gender = test_input($_POST["user_gender"]);
    }
    // Date of Birth validation.
    if (empty($_POST["user_dob"])) {
      $user_dobErr = "<p class ='error'>*Date of birth is required.</p>";
      $errors += 1;
    } else {
      $user_dob = test_input($_POST["user_dob"]);
    }
    // User terms validation
    if(empty($_POST['user_terms'])){
        $user_termsErr = "<p class ='error'>*Please read and agreed to the Globo Gym's terms and condition before continuing.</p>";
        $errors += 1;
    } else {
        $user_terms = "You have read and agreed to the Globo Gym's terms and condition";
    }
  }
  //SQL queries into database if there is zero errors
  if($errors == 0){
    // SQL query to insert new user member.
    $query = "INSERT INTO tbl_user VALUES('$user_id','$user_title','$user_fname','$user_lname','$user_type','$user_address','$user_email','$user_phone','$user_gender','$user_dob')";
    // Database connection with user query.
    if(mysqli_query($db_connect, $query)){
      echo "You have inserted a student information into the database";
      // SQL query to insert password if user into has been inserted to database
      $query_pass = "INSERT INTO tbl_cred VALUES ('$user_id','$user_password')";
    }else{
      //Testing Database connection
      //echo "Unable to inserted a student information into the database";
    }
    // Database connection with password query.
    if(mysqli_query($db_connect, $query_pass)){
      //echo "You have inserted a student password information into the database";
      //Return to index if user and password has been registered.
      header("Location: index.php?registration=success");
    }else{
      //Testing Database connection
      //echo "<br>Unable to inserted a student password information into the database";
    }
  }
  // Present the following form if there is no errors
if ($errors == 0) {?>
  <section class="form_container">
    <form action="join_now.php" method="post" class="form">
      <h1>SOCIETY MEMBERSHIP</h1>
      <label>STUDENT NUMBER*</label><input type="text" name="user_id" class="form_control">
      <label>TITLE*</label>
      <select name="user_title" class="form_control">
        <option value="Mr">Mr</option>
        <option value="Mrs">Mrs</option>
      </select>
      <label>FIRSTNAME*</label><input type="text" name="user_fname" class="form_control" size="20" maxlength="30">
      <label>LASTNAME*</label><input type="text" name="user_lname" class="form_control" size="20" maxlength="30">
      <label>GENDER*</label>
      <select name="user_gender" class="form_control">
        <option value="M">Male</option>
        <option value="F">Female</option>
      </select>
      <label>DATE OF BIRTH*</label><input type="date" name="user_dob" class="form_control" min="1920-01-01" max="2001-01-01">
      <label>ADDRESS</label> <input type="text" name="user_address" size="20" maxlength="200" class ="form_control">
      <label>PHONE NUMBER</label><input type="tel" name="user_phone" placeholder="eg.0861238200" class="form_control"/>
      <label>EMAIL ADDRESS*</label>
      <input type="email" name="user_email" class="form_control"/>
      <label>CONFIRM EMAIL*</label>
      <input type="email" name="confirm_user_email" class="form_control"/>
      <label>PASSWORD*</label><input type="password" class="form_control" name="user_password" required>
      <label>CONFIRM PASSWORD*</label><input type="password" class="form_control" name="user_password2" required>
      <div class="border"></div>
      <p><label>I have read and agreed to the Globo Gym's terms and condition.</label><input type="checkbox" name="user_terms" value="yes" class="form_checkbox"></p>
      <div class="border"></div>
      <input type="submit" name="submit" value="SUBMIT" class="formBtn" required>
    </form>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); ?>
<!--If there is an error present form with the following errors based on variables-->
<?php }else { ?>
    <section class="form_container">
      <form action="join_now.php" method="post" class="form">
        <h1>SOCIETY MEMBERSHIP</h1>
        <label>STUDENT NUMBER*</label><input type="text" name="user_id" class="form_control">
        <?php echo $user_idErr; ?>
        <label>TITLE*</label>
        <select name="user_title" class="form_control">
          <option value="Mr">Mr</option>
          <option value="Mrs">Mrs</optioAlrightn>
        </select>
        <?php echo $user_titleErr; ?>
        <label>FIRSTNAME*</label><input type="text" name="user_fname" class="form_control" size="20" maxlength="30">
        <?php echo $user_fnameErr; ?>
        <label>LASTNAME*</label><input type="text" name="user_lname" class="form_control" size="20" maxlength="30">
        <?php echo $user_lnameErr; ?>
        <label>GENDER*</label>
        <select name="user_gender" class="form_control">
          <option value="M">Male</option>
          <option value="F">Female</option>
        </select>
        <?php echo $user_genderErr; ?>
        <label>DATE OF BIRTH*</label><input type="date" name="user_dob" class="form_control" min="1920-01-01" max="2001-01-01">
        <?php echo $user_dobErr; ?>
        <label>ADDRESS</label> <input type="text" name="user_address" size="20" maxlength="200" class ="form_control">
        <?php echo $user_addressErr; ?>
        <label>PHONE NUMBER</label>
        <input type="tel" name="user_phone" placeholder="0861238200" class="form_control"/>
        <?php echo $user_phoneErr; ?>
        <label>EMAIL ADDRESS*</label>
        <input type="email" name="user_email" class="form_control"/>
        <label>CONFIRM EMAIL*</label>
        <input type="email" name="confirm_user_email" class="form_control"/>
        <?php echo $user_emailErr; ?>
        <label>PASSWORD*</label><input type="password" class="form_control" name="user_password" required>
        <label>COMFIRM PASSWORD*</label><input type="password" class="form_control" name="user_password2" required>
        <?php echo $user_passwordErr;?>
        <div class="border"></div>
        <p><label>I have read and agreed to Griffith Events terms and condition.</label><input type="checkbox" name="user_terms" value="yes" class="form_checkbox"></p>
        <?php echo $user_termsErr; ?>
        <div class="border"></div>
      <input type="submit" name="submit" value="SUBMIT" class="formBtn" required>
      </form>
    </section>
  <!--Footer which contains the disconnection to the database and footer section-->
  <?php include_once("footer.php"); }?>
