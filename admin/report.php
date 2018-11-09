<?php include("includes/admin_header.php"); ?>

<?php

$bu_id = $_SESSION['admin_info']['bu_id'];

$bu_id = $bu_id > 0 ? $bu_id : 0;


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
                                $rs_exams = Load_Report($connection, $bu_id);
                              ?>

                              <thead>
                                  <tr>
                                    <th>Ngày thi</th>
                                    <th>BU</th>
                                    <th>Ghi chú</th>
                                    <th>Số lượng tham gia</th>
                                    <th>Đạt</th>
                                    <th>Không đạt</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_exams)) {
                                        $id =  $row["exam_id"];
                                        $ngay_thi = $row["exam_date"];
                                        $ten_bu = $row["bu_name"];
                                        $so_luong =   $row["total"];
                                        $dat =   $row["DAT"];
                                        $khongdat =   $row["KHONGDAT"];
                                        $ghi_chu = $row["exam_desc"];

                                        echo "<tr><td>{$ngay_thi}</td>" ;
                                        echo "<td>{$ten_bu}</td>" ;
                                        echo "<td>{$ghi_chu}</td>" ;
                                        echo "<td>{$so_luong}</td>" ;
                                        echo "<td>{$dat}</td>" ;
                                        echo "<td>{$khongdat}</td>" ;
                                        echo "<td><a href='report_detail.php?id={$id}'>Chi tiết</a></td>";
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
