<?php include("includes/admin_header.php"); ?>


<?php 
if(!empty($_GET['sale_id']) && !empty($_GET['quiz_id'])){

     if ($_SESSION['admin_info']['id'] == 1) {
        $sale_id = mysqli_real_escape_string($connection, $_GET['sale_id']);
        $exam_id = mysqli_real_escape_string($connection, $_GET['quiz_id']);
        Result_Sales_Reset($connection, $sale_id, $exam_id);
     }
    
}

?>

  
