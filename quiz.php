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

            //remove item trong 
            if (isset($_SESSION["skip_question_id"])) {
               $key = array_search($_SESSION['current_question_index'], $_SESSION["skip_question_id"]) ;
                if ($key !== false ) {
                    unset($_SESSION['skip_question_id'][$key]);
                } 
            }
           


            //kiem tra la cuoi cung chua. Neu la cuoi cung thi nhay vao trang chuc mung, nguoc lai thi tiep tuc di chuyen toi cau hoi tiep theo
            if ($_SESSION['current_question_index'] ==  $_SESSION["quiz_total_questions"]-1  ) {
              $msg_to_user = "Bạn đang ở câu hỏi cuối cùng.<br> Vui lòng nhấn nút 'Quay lại' để kiểm tra hoặc nhấn nút NỘP BÀI để hoàn tất.";
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
              //ghi nhan current_inddex
              //echo "session: " . $_SESSION['current_question_index'];
              $current = $_SESSION['current_question_index'];
              //neu session skip da co thi moi tim, khong thi cu add
              if (isset($_SESSION["skip_question_id"])) {
                    $key = array_search($current, $_SESSION["skip_question_id"]);             
                    if ($key === false) {
                        $_SESSION["skip_question_id"][] = $_SESSION['current_question_index'];
                    } else {
                        //
                    }
              } else {
                    $_SESSION["skip_question_id"][] = $_SESSION['current_question_index'];

              }   
              $_SESSION['current_question_index'] +=1;
              //print_r($_SESSION["skip_question_id"]);
              header("Location: quiz.php");
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

                 <a href="#" class="btn  btn-md btn-danger nopbai"  id="btn-confirm">Nộp bài thi</a>

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

            <?php 
                if (isset($_SESSION["skip_question_id"]) && count($_SESSION["skip_question_id"]) >0) { 
                    //$list_skip_question = implode(",", $_SESSION["skip_question_id"] );
                    //echo $_SESSION["skip_question_id"];
                    $list_skip_question = "";
                    foreach ($_SESSION["skip_question_id"] as $key => $value) {
                        $list_skip_question  =  $list_skip_question . "," . ($value + 1)  ;
                     //   echo $list_skip_question;
                    }


                    
            ?>


                 <div class="modal-body">
                       <h3>Bạn còn các câu hỏi chưa trả lời: <?php echo substr($list_skip_question,1);  ?> </h3>  
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-info" data-dismiss="modal">Xem lại</button>
                  </div>   
            <?php 

                } else { ?>



                  <div class="modal-body">
                       <h3>Bạn có chắc chắn là muốn nộp bài và hoàn tất bài thi của mình?</h3>  
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Xem lại</button>
                <a href="finish.php?q=done" class="btn btn-danger btn-ok">Nộp bài</a>
                </div>



            <?php       
                }
            ?>
            


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
  
  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}




</script>

<script type="text/javascript">

  

  $(document).ready(function () {

      //neu chua xac dinh count down, thi set cookie countdown;
      var remain ;
      var remain_time;
       var austDay = getCookie("countedTime");
       if (austDay != "") {
            remain = austDay;
              remain_time = new Date(remain);
        } else {
            var now_quiz = new Date();
           // alert("cut" + now_quiz);
            now_quiz.setMinutes(now_quiz.getMinutes() + <?php echo $_SESSION["quiz_duration"]; ?>);

            setCookie("countedTime", now_quiz, 1);
            remain = getCookie("countedTime");

            remain_time = new Date(remain);
       }


      var gloryDay = new Date(remain_time.getFullYear(), remain_time.getMonth(), remain_time
        .getDate(), remain_time.getHours(), remain_time.getMinutes(),remain_time.getSeconds());
     

     // alert("Helo" + gloryDay);
      
     $('#mytimer').countdown({until: gloryDay, compact: true, format: 'MS', expiryUrl: '/quiz-sap/finish.php?q=done' });


     $('#btn-confirm').click( function() {

            if($("input:radio[name='chkChoice']").is(":checked")) {
            // set gia tri de biet la dang nhan Next
            $('#confirm-delete').modal('show');
          } else {
            alert('Vui lòng chọn 1 đáp án');
          }

           
     });
    /*

    $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });*/


  }); 
</script>


