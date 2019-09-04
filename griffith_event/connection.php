<?php
  // Start the session
  session_start();
  // Define the variables below  to connect to database
  DEFINE('DB_HOST', 'localhost');
  DEFINE('DB_USER', 'root');
  DEFINE('DB_PASSWORD', '');
  DEFINE('DB_NAME', 'griffith_event');
  // defined variables into db_connect variable to connect to database.
  $db_connect = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR
  die("Could not connect to the Database! ". mysqli_connect_error());
  // default character set to be used when sending data from and to the database server.
  mysqli_set_charset($db_connect, 'utf8');
?>
