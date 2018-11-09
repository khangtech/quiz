<?php include("includes/header_view.php");   ?>
<section class="t1">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <?php 

          $rs_wrong_answer = Load_Wrong_Answer($connection, $_SESSION["user_info"]['sale_id'],$_SESSION["exam_id"]);
      
           $countRow = mysqli_num_rows($rs_wrong_answer);

           if ($countRow >0) { ?>
             <div class="tableReport">
          <h2 class="mtTtl"><?php echo $countRow; ?> câu trả lời sai</h2>
          <br>
                <?php
                    
                    while($row = mysqli_fetch_assoc($rs_wrong_answer)) {
                      $question_id = $row["question_id"];
                      $question_text = Load_Question_Text($connection, $question_id);
                      $wrong_choice = $row["choice_id"];
                      $right_choice = Find_Right_Choice($connection, $question_id);
                      $rs_list_answer = Load_All_Answer($connection, $question_id); ?>
                      <h1 class="tlTtlReport">
                          <?php echo $question_text;  ?>
                      </h1>
                      
                      <ul class="anwserLstReport">
                      <?php             
                      while($sub_row = mysqli_fetch_assoc($rs_list_answer)) { 
                        if ($sub_row["choice_id"] == $wrong_choice) {
                            $q_class ="text-danger font-weight-bold";
                            $i_class = "glyphicon glyphicon-remove" ;
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
                    <?php } ?>
      </div>

        <?php     
           }
        ?>



       
      </div>
    </div>
  </div>
</section>


<?php include("includes/footer.php") ?>
