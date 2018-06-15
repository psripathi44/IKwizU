<?php
   $hostName = "localhost";
   $databaseName = "ikwizu";
   $username = "root";
   $password = "root";

   function showerror($connection){	   
      die("Error " . mysqli_errno($connection) . " : " . mysqli_error($connection));
   }

?>
