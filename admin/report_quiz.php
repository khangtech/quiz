<?php include("includes/admin_header.php"); ?>

<?php

$sale_id =  $_GET['sale_id'];
$quiz_id = $_GET['quiz_id'];

if ($quiz_id <=0 || $sale_id <=0) {
  header('Location: report.php');
}

?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <?php 
                            $sale_info = Load_Sales_Info($connection, $sale_id);
                            while($row = mysqli_fetch_assoc($sale_info)) {
                                $sale_full_name = $row["sale_name"];
                              }
                        ?>
                        <h2 class="page-header">Kết quả bài thi của <?php echo $sale_full_name; ?></h2>
                        <div class="col-xs-12">

                        <!-- BEGIN list bai thi -->
                        <?php 
                             $rs_bai_thi = Load_Bai_Thi($connection, $sale_id,$quiz_id);
                             $countRow = mysqli_num_rows($rs_bai_thi);
                        ?>
                          
                        <?php  if ($countRow >0) { ?>
                            <div class="tableReport">

                             <br>
                               <?php
                                     $count_dung = 0;
                                     $count_sai = 0;
                                     $count_total = $countRow;

                                   while($row = mysqli_fetch_assoc($rs_bai_thi)) {
                                     $question_id = $row["question_id"];
                                     $question_text = Load_Question_Text($connection, $question_id);
                                     $user_choice = $row["choice_id"];
                                     $right_choice = Find_Right_Choice($connection, $question_id);
                                     
                                     $rs_list_answer = Load_All_Answer($connection, $question_id); ?>
                                     <h4 class="tlTtlReport">
                                         <?php echo $question_text;  ?>
                                     </h4>

                                    
                                     <ul class="anwserLstReport">
                                     <?php
                                     
                                     while($sub_row = mysqli_fetch_assoc($rs_list_answer)) { 
                                       if ($sub_row["choice_id"] == $user_choice && $user_choice == $right_choice) {
                                            $q_class="text-success font-weight-bold";
                                            $i_class= "glyphicon glyphicon-ok" ;  
                                            $count_dung +=1;
                                       } else if ($sub_row["choice_id"] == $user_choice && $user_choice != $right_choice) {
                                            $q_class ="text-danger font-weight-bold";
                                            $i_class = "glyphicon glyphicon-remove" ;
                                            $count_sai +=1;
                                       } else if ($sub_row["choice_id"] == $right_choice) {
                                            $q_class="text-success font-weight-bold";
                                            $i_class= "glyphicon glyphicon-ok" ; 
                                       } else {
                                           $q_class ="text-normal";
                                           $i_class ="";
                                       }
                                     ?>
                                     <li>
                                         <span class="<?php echo $i_class; ?>"></span>
                                         <span class="<?php echo $q_class; ?>">
                                         <?php echo $sub_row["choice"];  ?>  
                                       </span>
                                     </li>  
                                    <?php        
                                     }
                                   ?>
                                     </ul>      
                                     <br>
                                   <?php } ?>
                     </div>
               
                       <?php     
                          }
                       ?>
                  
                       <h3 class="text-danger">Số câu sai: <?php echo $count_sai; ?> </h3>
                       <h3 class="text-success">Số câu đúng: <?php echo $count_dung; ?> </h3>




                        <!-- END list bai thi -->

                        </div>

                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
<?php include ("includes/admin_footer.php"); ?>
