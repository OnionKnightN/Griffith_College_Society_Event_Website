<!--Header which contains the connection to the database and navigation bar-->
<?php include_once("header.php"); ?>
<?php
  // Validating for logged in users. If true then redirect to index.php
  if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
    header("Location: index.php?error=forbidden");
  }
?>
<?php
  // Start with form URL validation from the login form.
  $loginErr = "";
  // Sanatize data from login input
  if (isset($_POST["submit"])){
    function sanitize($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $data = mysqli_real_escape_string($GLOBALS["db_connect"], $data);
      return $data;
    }
  // Sanitize email input.
  $email = sanitize($_POST["email"]);
  // Sanitize password input.
  $password = mysqli_real_escape_string($db_connect, $_POST["psw"]);
  // Lookup email from database
  $email_lookup = "SELECT user_id, user_email, user_type FROM tbl_user WHERE user_email = '$email' LIMIT 1";
  $pull_email = mysqli_query($db_connect, $email_lookup);
  $num = mysqli_num_rows($pull_email);
  // If email is in the database execute if statement
  if($num==1){
    while($row = mysqli_fetch_array($pull_email)){
      // Lookup password from database
      $pass_lookup = "SELECT user_pass FROM tbl_cred WHERE user_id = '".$row["user_id"]."' LIMIT 1";
      $pull_pass = mysqli_query($db_connect, $pass_lookup);
      // If password matches the user_id from the email execute if statement
      $num = mysqli_num_rows($pull_pass);
        if($num==1){
          while($row2 = mysqli_fetch_assoc($pull_pass)){
            // Test if password is correct
            $verify =  $row2["user_pass"];
            if(password_verify($password, $verify)){
                //if password is correct then login=success
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["user_email"] = $row["user_email"];
                $_SESSION["user_type"] = $row["user_type"];
                // Redirect to index page succesful
                header("Location: index.php?login=success");
            }else{
              $loginErr = "<p class ='login_error'>You have provide an incorrect Email or Password.</p>";
              //if password is incorrect then redirect to login page succesful
              //header("Location: login.php?login=error");
            }
          }
        }
      }
    }else{
      $loginErr = "<p class ='login_error'>You have provide an incorrect Email or Password.</p>";
      //if email is incorrect then redirect to login page succesful
      //header("Location: login.php?login=error");
    }
  }
?>
  <section class = "login_area">
    <div class = "login">
        <form action="login.php" method="POST">
          <h1>MEMBER LOGIN</h1>
          <label for="email"><b>Email:</b></label><input type="email" name="email" class="login_input"/>
          <label><b>Password:</b></label><input type="password" name="psw" class="login_input" required>
          <?php echo $loginErr; ?>
          <input type="submit" name="submit" value="Login" class="loginBtn" >
        </form>
    </div>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php");?>
