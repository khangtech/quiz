<?php include("includes/header.php");   ?>



<section class="winner">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="winnerBox">
          <h2>Cảm ơn bạn đã hoàn tất bài thi!</h2>

          <?php
              $sale_id = $_SESSION["user_info"]['sale_id'];
              $exam_id = $_SESSION['exam_id'];
              //tinh toan so cau hoi dung
              $total_right_answer = Count_Right_Answer($connection,$sale_id,$exam_id);
             

              while($row = mysqli_fetch_assoc($total_right_answer)) {
                  $so_cau_tra_loi_dung = $row['total'];
              }

            

              $total_question = $_SESSION["quiz_total_questions"] ;
              $so_cau_tra_loi_sai = $total_question - $so_cau_tra_loi_dung ;

              //tinh %
              $rate = ($so_cau_tra_loi_dung) * 100 / $total_question;
          ?>
            <?php
                if ($rate >= $_SESSION["quiz_passed"]) {
                  echo '<p class="winnerImg"><img src="images/img_winner.png" alt="" /></p>';
                } else { ?>
                  <p class="winnerImg"><img src="images/sad.png" alt="" /></p>
            <?php
                }
            ?>


          <p class="winnerTxt">
            <span>
             Bạn đã trả lời đúng <?php echo $so_cau_tra_loi_dung; ?>/<?php echo  $total_question ; ?>
             câu hỏi.</span>
           </p>

          <?php 
            if ($so_cau_tra_loi_sai >0) { ?>
              <p class="winnerTxt"> 
             <!-- <a class="btn btn-default btn-info" href="/quiz-sap/results.php">Xem các  câu trả lời sai</a> -->
              </p>
          <?php 
            } 
          ?> 
          

          <?php
              //update trang thai thi xong
              $status ='done';
              Update_Log($connection,$_SESSION["user_info"]["sale_id"],$_SESSION["exam_id"],$status);
              //bỏ gán time kết thúc
              $_SESSION["end_time"] = '';
              unset($_SESSION['skip_question_id']);

              //  $_SESSION["user_info"] ='';
              //  session_destroy();
          ?>
        </div>
      </div>
    </div>
  </div>
</section>


<?php include("includes/footer.php") ?>
