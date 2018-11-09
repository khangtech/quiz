<?php session_start() ; ?>
<?php

    if(isset($_SESSION['time_begin']) && isset($_SESSION['time_out'])) {

      //$_SESSION['time_out'] = $_SESSION['time_out'] - 1000;
      echo $_SESSION['time_begin'] . ';' . $_SESSION['time_out'] ;



    } else {

      $current_timestamp = time($current_date);
      $_SESSION['time_begin'] = $current_timestamp ;

      $timeout =  $current_timestamp + (1 * 60 * 1000);

      $_SESSION['time_out'] = $timeout;
    }




?>
