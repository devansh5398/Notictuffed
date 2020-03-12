<?php
  $server = "localhost";
  $dbuser = "id12903293_devansh";
  $dbpass = "Har427Mahadev";
  $db = "id12903293_root";

  $conn = mysqli_connect($server, $dbuser, $dbpass, $db);
  if(!$conn)
    die("Connection failed: ".mysqli_connect_error());
?>
