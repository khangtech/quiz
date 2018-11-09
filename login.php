<?php session_start() ; ?>
<?php include("includes/db.php") ?>
<?php include('includes/functions.php') ?>
<?php
  if (isset($_POST["btnSubmit"])) {
    //kiem tra BU co thi hay khong?
    if (isset($_POST["cboBU"]) && $_POST["cboBU"] >0 ) {
      $bu_id = mysqli_real_escape_string($connection,$_POST["cboBU"]);
      $exam_id = Check_Exam_Is_Running($connection, $bu_id);
      if ($exam_id > 0 ) {
        //neu co thi doc toan bo thong tin cuoc thi
        // doc info cua CuocThi, bao gom so luong cau hoi, diem so can dat, thoi gian lam bai
        $rs_exam_info = Load_Exam_Info($connection,$exam_id);

        $countRow = mysqli_num_rows($rs_exam_info);

        if ($countRow >0) {
          while ($row = mysqli_fetch_assoc($rs_exam_info)) {
            $_SESSION["quiz_duration"] = $row["exam_duration"];
            $_SESSION["quiz_passed"] = $row["exam_passed"];
            $_SESSION["quiz_total_questions"] = $row["total_questions"];
          }

          //load cac cau hoi 
          $all_question = array();
          $rs_topic = Load_Topic_By_Exam($connection,$exam_id);
          while($row = mysqli_fetch_assoc($rs_topic)) {
            $topic_id = $row['topic_id'];
            $quantity = $row['number_questions'];
              // ung voi chu de nay thi load cac cau hoi voi so luong tuong ung
            $rs_question_list = Load_Question_By_Topic_And_Number($connection,$topic_id, $quantity);
            while ($row = mysqli_fetch_assoc($rs_question_list)) {
              $all_question[] = $row;
            }
          }
          // luu cac cau hoi nay vao trong session cua user nay
          $_SESSION['questions_array']= $all_question;
          $_SESSION['current_question_index']= 0;

        } 


        //kiem tra user co ton tai trong bu hien tai không?
        if (isset($_POST["txtMaSo"]) && !empty($_POST["txtMaSo"])) { 
            $sale_code = mysqli_real_escape_string($connection,$_POST["txtMaSo"]);
            $sale_id = Check_Sales_Login($connection, $sale_code, $bu_id);
            if ($sale_id > 0) {
               //load thong tin cua user va luu lai
               $sale_info = Load_Sales_Info($connection, $sale_id);
               while ($row = mysqli_fetch_assoc($sale_info)) {
                      $_SESSION["user_info"] = $row;
               }
               $_SESSION["exam_id"] = $exam_id;
              //kiem tra user dang lam dở dang hay đã kết thúc bài thi 
              $check_log = Check_Exam_Logs($connection, $sale_id, $exam_id);
              
              if ($check_log == "empty") {
                  // xoa skip truoc do
                  unset($_SESSION['skip_question_id']);  
                  
                  //nhay vao trang welcome
                  header("Location: welcome.php");
              } else {
                  if ($check_log == "done") {
                    $err_msg = "Bạn đã hoàn tất bài thi!" ;
                  }  else {
                    //cho làm tiếp tục, các câu hỏi dang dở
                    //tuy nhien chi tiep tuc khi còn thoi gian
                    //kiem tra thoi gian
                   // date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $endtime = Check_Exam_Logs_Time($connection, $sale_id, $exam_id);
                  //  echo "endtime:" . $endtime;
                    $endtimeamount = strtotime($endtime);
                  //  echo "<br>date:". date('Y-m-d H:i:s');
                     date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $currenttimeamount = strtotime(date('Y-m-d H:i:s'));
                  //  echo "<br>end ampunt" . $endtimeamount . "<br>current amount" .   $currenttime;
                    $remainammount = $endtimeamount - $currenttimeamount;

                    if ($remainammount >0 ) {
                      //set thoi gian con lai
                      $_SESSION["end_time"] = $endtime;
                     
                        //print ( $_SESSION["end_time"]);
                         header("Location: quiz.php");  
                    } else {
                      $err_msg = "Bạn đã không còn thời gian để làm bài" ;
                    }
                  }
              }
                
            } else {
              $err_msg = "Mã nhân viên không tồn tại!";
            }
        } else {
          $err_msg ="Vui lòng nhập mã số nhân viên!";
        }
      } else {
        $err_msg  = "Chưa tổ chức thi. Vui lòng liên hệ SAP Team";
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="vn">
<head>
<title>Green Feed</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" media="screen" rel="stylesheet" type="text/css" />

</head>
<body class="login">
<section class="loginBox">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <h1><a href="#"><img src="images/logo.png" alt="" /></a></h1>

        <div class="loginWrapper">
          <h2>Hệ thống thi trắc nghiệm</h2>
          
        <div class="loginBoxInner">

          <form action="" method="post">
            <ul>
              <li>Vui lòng chọn SAP Team và nhập mã số nhân viên</li>
               <li>
                <select name="cboBU" class="form-control pass">
                    <option value="0">Vui lòng chọn SAP Team </option>
                    <?php
                        $rs_bu = Load_All_BU($connection);
                        while ($row = mysqli_fetch_assoc($rs_bu)) {
                          $bu_id = $row["bu_id"];
                          $bu_name =$row["bu_name"]; ?>
                          <option value="<?php echo $bu_id?>"><?php echo $bu_name ?></option>
                    <?php
                        }
                    ?>
                </select>
              </li>

              <li>
                <input type="text" id="txtMaSo" name="txtMaSo" value="<?php echo $ma_so_nhan_vien;  ?>" placeholder="Mã số nhân viên (XXXXXX)" class="user" />
              </li>
             
            </ul>

            <?php if ($err_msg!="") {
                        echo "<h3>" . $err_msg . "</h3>";
                  }
            ?>

            <p class="submitBtn">
              <input type="submit" name="btnSubmit" value="Đăng nhập" class="submit" />
            </p>
          </form>
        </div>

      </div>
      </div>
    </div>
  </div>
  <p class="copyright">Copyright ©<?php echo date("Y") ?> GreenFeed VietNam. All rights reserved</p>
</section>
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

</body>
</html>
