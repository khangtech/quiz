<?php include("includes/admin_header.php"); ?>

<?php

$bu_id = $_SESSION['admin_info']['bu_id'];

$exam_id =  $_GET['id'];

if ($exam_id <=0) {
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
                        <h2 class="page-header">Kết quả cuộc thi</h2>
                        <div class="col-xs-12">



                          <table class="table table-bordered table-hover">

                              <?php
                                $rs_exams = Load_Report_Detail($connection, $bu_id, $exam_id);


                              ?>

                              <thead>
                                  <tr>
                                    <th>Mã NV</th>
                                    <th>Họ tên</th>
                                    <th>Số câu trả lời ĐÚNG</th>
                                    <th>Số câu trả lời SAI</th>
                                    <th>Kết quả</th>

                                    <?php 
                                       if ($_SESSION['admin_info']['id'] == 1) {?>
                                          <th>Reset</th>
                                           <th>Bài thi</th>
                                     <?php } ?>     
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_exams)) {
                                        $ma_nv = $row["sale_code"];
                                        $ten_nv = $row["sale_name"];
                                        $dat =   $row["right_answer"];
                                        $khongdat =   $row["wrong_answer"];
                                        $total_question = $row["exam_total"];
                                        $sale_id = $row["sale_id"];
                                        $link_reset = '<a href="reset.php?sale_id=' . $sale_id . '&quiz_id=' . $exam_id . '">Reset</a>';
                                        $link_view = '<a href="report_quiz.php?sale_id=' . $sale_id . '&quiz_id=' . $exam_id . '">Bài thi</a>';
                                        $exam_passed =  $row["exam_passed"];
                                        $tileketqua = $dat * 100 / $total_question;
                                       // echo $tileketqua;
                                       // $tile = $row["percent"];
                                        $ketqua = $tileketqua >= $exam_passed  ? 'Đạt' : 'Không đạt';
                                        echo "<tr><td>{$ma_nv}</td>" ;
                                        echo "<td>{$ten_nv}</td>" ;
                                        echo "<td>{$dat}</td>" ;
                                        echo "<td>{$khongdat}</td>" ;
                                        echo "<td>{$ketqua}</td>";
                                        if ($_SESSION['admin_info']['id'] == 1) {
                                          echo "<td>{$link_reset}</td>";
                                          echo "<td>{$link_view}</td>";

                                        }
                                        $counter+=1;
                                      }

                                      mysqli_free_result($rs_exams);

                                  ?>
                              </tbody>
                          </table>

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
