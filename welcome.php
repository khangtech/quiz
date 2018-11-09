<?php include("includes/header_welcome.php");   ?>

<?php


if ($_POST["btnDo"]) {

  //insert vào trong log để quản lý
   $status = "running";
   $quiz_timer = Insert_To_Log($connection,$_SESSION["user_info"]["sale_id"],$_SESSION["exam_id"],$status,$_SESSION["quiz_duration"]);

   $arr_quiz_timer = explode(";", $quiz_timer);

   //echo $quiz_timer;

   $_SESSION["start_time"] =  $arr_quiz_timer[0];
   $_SESSION["end_time"] = $arr_quiz_timer[1];
   // actual timer on client
   
  // print_r($_SESSION['questions_array']);
  //nhay vao quiz
   header("Location: quiz.php"); 
} 


?>


<section class="topCont">
  <h2 class="mtTtl">hướng dẫn làm bài</h2>
  <div class="container topContBox">
    <div class="row">
      <div class="col-sm-6 col-md-6 col-lg-6">
        <form  name="frmDo" method="post">
        <div class="topContL">
          
          <h2>Xin chào <strong><?php echo $_SESSION["user_info"]["sale_name"]; ?></strong></h2>
          <p>Vui lòng đọc kỹ hướng dẫn bên dưới trước khi làm bài trắc nghiệm</p>
          <ul class="welcome_note">
              <li>Thời gian làm bài: <strong>
                <?php echo ($_SESSION["quiz_duration"] == "" ? '' : $_SESSION["quiz_duration"])   ?> phút</strong> (tính từ lúc bạn nhấn nút Làm bài)</li>
              <li>Tổng số câu hỏi: <strong><?php echo ($_SESSION["quiz_total_questions"] == "" ? '' : $_SESSION["quiz_total_questions"])  ?></strong></li>
              <li>Với mỗi câu hỏi: <strong>chọn 1 đáp án duy nhất</strong></li>
              <li>Kết quả đạt: Nếu bạn trả lời đúng từ <strong><?php echo ($_SESSION["quiz_passed"] == "" ? '' : $_SESSION["quiz_passed"])  ?>%</strong> trở lên</li>
          </ul>
          <br>
          <p>Nếu đã sẵn sàng, vui lòng nhấn nút Làm Bài. Mọi ý kiến vui lòng liên hệ với BU Marketing hoặc Ban tổ chức.</p>

          <p class="topContBtn">
            <input type="submit"  value="Làm bài" name="btnDo">
          </p>
        </div>
      </form>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="topContR"><img src="images/imgHome.png" alt="" /> </div>
      </div>
    </div>
  </div>
</section>
<?php include("includes/footer.php") ?>
