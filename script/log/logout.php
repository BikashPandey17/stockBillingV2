<?php
  
  
  // Logout script just destroy session and cookie
  session_start();
    unset($_SESSION['logged']); unset($_SESSION['des']);
  session_destroy();
  setcookie("OBA_LOGGED_IN", "", time()-3600, "/");
  
  header("location:../../");
  
 ?> 