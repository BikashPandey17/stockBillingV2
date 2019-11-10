<?php

  // Database Credentials
  // Author : Kalyan Majumdar
  
  $host = "localhost";//"mysql51-011.wc1.dfw1.stabletransit.com"
  $port = 3306; // Default
  $uname = "root"; //
  $pwd = "";//""
  $db = "stock_db";//stock_db
  
  $con = mysqli_connect($host, $uname, $pwd, $db, $port) or die("Failed to connect to database ...");
  
  if(mysqli_connect_errno())
  {
	echo "Failed to connect to database ...".mysqli_connect_error();
  } 
?>