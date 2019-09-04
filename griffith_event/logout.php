<?php
// connecting to the database based on connection.php
include_once("connection.php");
    // When logout has been clicked then execute if staement
    if(isset($_GET["logout"])){
        // Destroy all session variables.
        session_destroy();
        unset($_SESSION["user_email"]);
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_type"]);
        header("Location: login.php?logout=successful");
    // If the URL isn't set, validate the sessions.
    }else{
        header("Location: index.php");
    }
// Closing the Database
mysqli_close($db_connect);
?>
