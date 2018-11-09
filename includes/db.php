<?php
/* local 
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASSWORD","");
define("DB_NAME","2017_quiz");

*/

/* sever */

define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASSWORD","");
define("DB_NAME","2018_quiz_sap"); 




global $connection;
$connection= mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) ;
if (!$connection) {
    die("Can not connect");
//  echo "We connnected";
}



//printf("Initial character set: %s\n", $connection->character_set_name());

/* change character set to utf8 */
if (!mysqli_set_charset($connection, "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($connection));
    exit();
}


?>
