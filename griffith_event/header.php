<!--Connection to the Database using connection.php-->
<?php require_once("connection.php"); ?>
<!DOCTYPE html>
<html lang = "en">
  <head>
    <meta charset="utf-8"/>
    <title>Griffith Events</title>
    <meta name="viewport" content="width=device-width, intial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <div><a href="index.php"><img src="img/griffith_college_logo.png" alt= "home logo" id ="logo"></a></div>
      <label id= "burger" for="toggle">&#9776;</label>
      <input type="checkbox" id="toggle"/>
      <nav class="nav_main">
        <ul class="nav_list">
          <li class = "normal"><a href="societies.php">SOCIETIES</a></li>
          <!-- Navigation bar changes based on user type -->
          <?php
            if(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])){
              if($_SESSION["user_type"]=="Admin"){
                echo '<li class = "special"><a href="admin_menu.php">ADMIN MENU</a></li>';
              }elseif($_SESSION["user_type"]=="Society"){
                echo '<li class = "normal"><a href="events.php">EVENTS</a></li>';
                echo '<li class = "special"><a href="society_menu.php">SOCIETY MENU</a></li>';
              }else {
                echo '<li class = "special"><a href="events.php">EVENTS</a></li>';
              }
              echo '<li class = "special"><a href="logout.php?logout=true">LOGOUT</a></li>';
            //if there is no logged in user present the following navigation link
            }else if(!(isset($_SESSION["user_email"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"]))){
                echo '<li class = "special"><a href="join_now.php">JOIN NOW</a></li>
                      <li class = "special"><a href="login.php">LOG IN</a></li>';
            }else{
                // Redirect to logout to reset all sessions if no one is logged in
                header("Location: logout.php?logout=true");
            }
          ?>
        </ul>
      </nav>
    </header>
