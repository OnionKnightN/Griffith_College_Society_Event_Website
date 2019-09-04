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
  <section class = "menu_container">
    <section class="menu">
      <div class="menu_info">
        <h3><b>GRIFFTH COLLEGE EVENTS</b></h3>
        <p>The link below will allow you to view all the current Griffith College Events created by societies.
        All the events must be approved to be viewed and availiable among Griffith College Students Society Members.
        Each event created must be approved by the Griffith College Student Union.</p><br>
        <p class = "button_link"><a href="admin_event_all.php"><b>EVENT LINK</b></a></p>
      </div>
      <div class="menu_info">
        <h3><b>APPROVE/DELETE EVENTS</b></h3>
        <p>The link below can only be accessed by the admin of the website. You will be able to view all the
          events created by societies.These are not availiable among Griffith College Students Society Members.
          These events must be approved.You will be able to delete events if necessary.</p><br>
        <p class = "button_link"><a href="admin_event.php"><b>EVENT ADMIN</b></a></p>
      </div>
      <div class="menu_info">
        <h3><b>GRIFFITH COLLEGE SOCIETY</b></h3>
        <p>The link below will allow you to create new societies for Griffith College or delete existing societites
        from Griffith College. Only the admin is able to make the decision to create or delete societies.societies.these
        are availible to view among the public, college students, and college societies.</p><br>
        <p class = "button_link"><a href="admin_society.php"><b>SOCIETY ADMIN</b></a></p>
      </div>
    </section>
  </section>
<!--Footer which contains the disconnection to the database and footer section-->
<?php include_once("footer.php"); ?>
