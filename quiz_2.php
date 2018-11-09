<?php include("includes/header.php");   ?>
<?php 
//kiem tra bien post
if ($_POST['txtAction']) {
      if ($_POST['txtAction']=='next') {
        //kiem tra cau hoi nay co trong db chua,
        if (isset($_POST['chkChoice'])) {
            $choice_value = $_POST['chkChoice'];
            $sale_id = $_SESSION["user_info"]['sale_id'];
            $exam_id = $_SESSION["exam_id"];
            $question_id = $_POST["txtQuestionID"];
            $is_choice_ok = Is_Right_Choice($connection, $choice_value) == 1 ? 1 : 0;
            if (Check_Exist_Answer($connection,$sale_id,$exam_id, $question_id)) {
                Edit_Answer($connection,$sale_id, $exam_id, $question_id, $choice_value, $is_choice_ok);
            } else {
                Insert_Answer($connection,$sale_id, $exam_id, $question_id, $choice_value, $is_choice_ok);
            }

            //kiem tra la cuoi cung chua. Neu la cuoi cung thi nhay vao trang chuc mung, nguoc lai thi tiep tuc di chuyen toi cau hoi tiep theo
            if ($_SESSION['current_question_index'] ==  $_SESSION["quiz_total_questions"]-1  ) {
              $msg_to_user = "Bạn đã trả lời xong câu hỏi cuối cùng.<br> Vui lòng nhấn nút 'Quay lại' để kiểm tra hoặc nhấn nút NỘP BÀI để hoàn tất.";
               // header("Location: finish.php");
            } else {
              $_SESSION['current_question_index'] +=1;
              header("Location: quiz.php");
            }


        } // du lieu hop le, user co chon mot option

      } // end xu ly NEXT
      else {
        if ($_POST['txtAction']=='back') {
          if ($_SESSION['current_question_index'] > 0 ) {
                $_SESSION['current_question_index'] -=1;
          }
        } else {
            if ($_POST['txtAction']=='skip') {
              //neu cau hoi nay chua có cau tra loi mà skip thi luu lai
               $sale_id = $_SESSION["user_info"]['sale_id'];
               $exam_id = $_SESSION["exam_id"];
               $question_id = $_POST["txtQuestionID"];

               echo "Lan dau tien" . $question_id;
              


              // khong co cau tra loi 
              if (Check_Exist_Answer($connection,$sale_id,$exam_id, $question_id)==0) {
                  // search xem co chua moi add
                 
                  if (!empty($_SESSION["skip_question_id"])) {
                      $key = array_search($question_id, $_SESSION["skip_question_id"]);
                      if($key >= 0) {  
                      } else {
                        $_SESSION["skip_question_id"][] = $question_id; ?>

                        <pre>
                        <?php   
                         var_dump($_SESSION["skip_question_id"]); ?>

                       </pre>
                  <?php      
                      }   
                  }  else {
                      $_SESSION["skip_question_id"][] = $question_id;
                  }   
              }

              if ($_SESSION['current_question_index'] == $_SESSION["quiz_total_questions"] -1 )
               {
                 $msg_to_user = "Bạn đang ở câu hỏi cuối cùng. Vui lòng chọn 1 đáp án.";  
                  
               } else {
                  //echo "<br>";
                  $_SESSION['current_question_index'] +=1;
                  header("Location: quiz.php");
                 
               }
            }
        }
      }
}
?>
<section class="tl">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div  class="time">
            <span>Thời gian còn lại</span>
            <span id="mytimer"></span>

            <?php if (strlen($msg_to_user) >0) { ?>
              <div class="text-danger" style="margin: 10px 0px"><?php echo $msg_to_user; ?></div>
            <?php 
            } ?>
        </div>



        <div class="time">
            
        </div>
        <div class="tlBox">
          <div class="tlTtl clearfix">
            <div class="tlTtlL"> <span>Câu hỏi</span><?php echo ($_SESSION['current_question_index'] + 1)  . '/' . $_SESSION["quiz_total_questions"] ?> </div>
            <div class="tlTtlR"> 

 <?php
                  $current_index = $_SESSION['current_question_index'] ;
                  $current_question_id = $_SESSION['questions_array'][$current_index]['question_id'];
                  //var_dump($_SESSION['questions_array'][$current_index]);
                  if (strlen($_SESSION['questions_array'][$current_index]['question_image']) <=1 || is_null($_SESSION['questions_array'][$current_index]['question_image'])) {

                    echo $_SESSION['questions_array'][$current_index]['question'];

                  
                  } else {
                    
                      echo $_SESSION['questions_array'][$current_index]['question'];

                    echo '<br><img width="1000px" style="margin-top:20px; max-width: 100%"  src="/quiz-sap/images/' . $_SESSION['questions_array'][$current_index]['question_image'] . '" />'; 
                  }

                  
              ?>

            </div>
          </div>
          <div class="anwser">
            <form action="" method="post" id="frmQuiz">

              <input type="hidden" name="txtQuestionID" value="<?php echo $current_question_id; ?>">
              <ul class="anwserLst">
                <?php
                    $rs_answer = Load_All_Answer($connection, $current_question_id);
                    // neu la next thi chi can load
                    $selected_answer = Selected_Answer($connection,$_SESSION["user_info"]['sale_id'],$_SESSION["exam_id"], $_SESSION['questions_array'][$current_index]['question_id']);
                    while($row = mysqli_fetch_assoc($rs_answer)) {
                    ?>
                      <li>
                        <input type="radio" id="chkChoice<?php echo $row["choice_id"] ?>" value="<?php echo $row["choice_id"] ?>"
                        <?php echo ($selected_answer == $row["choice_id"]) ? 'checked' : '' ?> name="chkChoice">
                        <label id="<?php echo $row["is_right_choice"]; ?>"  for="chkChoice<?php echo $row["choice_id"] ?>"><?php echo $row["choice"]; ?></label>
                        <div class="check">
                          <div class="inside"></div>
                        </div>
                      </li>
                    <?php } ?>
              </ul>
                <p class="anwserBtn clearfix">

                  <?php if($_SESSION['current_question_index']>0) { ?>
                    <input type="button" class="btn btn-md"  onclick="Back_User_Choice()"  name="btnBack" value="< Quay lại" />
                  <?php  }?>
                <input type="button" class="btn btn-md" onclick="Check_User_Choice()"  name="btnNext" value="Chọn" />

                 <input type="button" class="btn btn-md" style="display: <?php  echo ($_SESSION['current_question_index'] == $_SESSION["quiz_total_questions"] -1) ? 'none' : 'inline-block';  ?>" onclick="Check_Skip_Choice()"  name="btnSkip" value="Bỏ qua >>" />

                 <a href="#" class="btn  btn-md btn-danger nopbai" data-href="finish.php?q=done" data-toggle="modal" data-target="#confirm-delete">Nộp bài thi</a>

                <input type="hidden" id="txtAction" name="txtAction" />
                </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>XÁC NHẬN</h1>
            </div>
            <div class="modal-body">
                <h3>Bạn có chắc chắn là muốn nộp bài và hoàn tất bài thi của mình?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Xem lại</button>
                <a class="btn btn-danger btn-ok">Nộp bài</a>
            </div>
        </div>
    </div>
</div>




<script>
function Check_User_Choice() {
  $(document).ready(function() {
    if($("input:radio[name='chkChoice']").is(":checked")) {
      // set gia tri de biet la dang nhan Next
      $("#txtAction").val('next');
      $( "#frmQuiz").submit();
    } else {
      alert('Vui lòng chọn 1 đáp án');
    }
  });
}

function Check_Skip_Choice() {
  $(document).ready(function() {
      $("#txtAction").val('skip');
      $( "#frmQuiz").submit();
  });
}



function Back_User_Choice() {
  $(document).ready(function() {
      $("#txtAction").val('back');
      $( "#frmQuiz").submit();
  });
}

function Alert_Confirm() {
   $(document).ready(function() {
    $('#hplSubmit').click(function() {
        if (!confirm("Hello")) {
          return false;
        }
    });
  });
}

/*
function Load_Old_Answer() {
  $(document).ready(function() {
      //its checked
      alert('hello');
      $('#act').val('back');
      $( "#frmQuiz").submit();

  });
} */
</script>






<?php include("includes/footer.php") ?>


<script type="text/javascript">  
  $(document).ready(function () {
     <?php if (isset($_SESSION["end_time"])) { ?>
     var austDay = new Date("<?php  echo $_SESSION["end_time"]; ?>") ;
     $('#mytimer').countdown({until: austDay, compact: true, format: 'MS', expiryUrl: '/quiz-sap/finish.php?q=done' });
     <?php } ?>


     $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
  }); 
</script>


