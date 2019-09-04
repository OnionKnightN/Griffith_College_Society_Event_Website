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
    <section class = "menu_container">
      <section class="menu">
        <div class="menu_info">
          <h3><b>SOCIETY EVENTS</b></h3>
          <p>The link below will allow you to view all the Griffith College Events created by the login Society.
          All the events must be approved to be viewed and availiable among Griffith College Students Society Members.
          Each event created must be approved by the Griffith College Student Union.</p><br>
          <p class = "button_link"><a href="society_events.php"><b>VIEW EVENT</b></a></p>
        </div>
        <div class="menu_info">
          <h3><b>CREATE EVENTS</b></h3>
          <p>The link below can only be accessed by Griffith College Societies. You will be able to create new
            events for Griffith College Society Members.These are not availiable among Griffith College Society Members.
            You will be able view these events once they are approved by the Student Union.</p><br>
          <p class = "button_link"><a href="society_event_create.php"><b>CREATE EVENT</b></a></p>
        </div>
        <div class="menu_info">
          <h3><b>UPDATE SOCIETY</b></h3>
          <p>The link below will allow you to update details about your society in Griffith College.You must
          be part of Society Group Organisers to update society details.These are limited towards its
          description, meet up days and phone number. Details are restricted as it is viewed among the public.</p><br>
          <p class = "button_link"><a href="society_update.php"><b>UPDATE</b></a></p>
        </div>
      </section>
    </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); ?>
