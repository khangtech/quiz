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
                        <h2 class="page-header">Thống kê theo chủ đề</h2>
                        <div class="col-xs-12">
                        <?php
                        if ($bu_id >0) { ?>

                          <table class="table table-bordered table-hover">

                              <?php
                                $rs_exams = Load_All_Exam_Per_BU($connection, $bu_id );

                                while ($row = mysqli_fetch_assoc($rs_exams)) { ?>
                                <tr>
                                    <td colspan=3><h4><?php echo $row['exam_date']; ?></h4></td>
                                </tr>
                                <tr>
                                    <th>Chủ đề</td>
                                    <th>Số câu đúng</th>
                                    <th>Số câu sai</th>
                                </tr>
                              <?php
                                // ung voi tung ngay thi load tuong ung
                                $rs_topic_bu = Load_Topic_Report_Per_BU($connection, $bu_id, $row['exam_id']) ;
                                    while($sub_row = mysqli_fetch_assoc($rs_topic_bu)) {?>
                                      <tr>
                                          <td><?php echo $sub_row['topic_name']; ?></td>
                                          <td><?php echo $sub_row['total_right']; ?></td>
                                          <td><?php echo $sub_row['total_wrong']; ?></td>
                                      </tr>
                                <?php
                                    }
                                      mysqli_free_result($rs_topic_bu);

                                }
                                mysqli_free_result($rs_exams);
                              ?>
                          </table>


                        <?php
                      } else { ?>

                        <table class="table table-bordered table-hover">

                            <?php
                              $rs_bu = Load_All_BU($connection);

                              while ($row = mysqli_fetch_assoc($rs_bu)) { ?>
                              <tr>
                                  <td colspan=3><h4><?php echo $row['bu_name']; ?></h4></td>
                              </tr>
                              <tr>
                                  <th>Chủ đề</td>
                                  <th>Số câu đúng</th>
                                  <th>Số câu sai</th>
                              </tr>
                            <?php
                              // ung voi tung ngay thi load tuong ung
                              $rs_topic_bu = Load_Topic_Report_Per_BU_Admin($connection, $row['bu_id']) ;
                                  while($sub_row = mysqli_fetch_assoc($rs_topic_bu)) {?>
                                    <tr>
                                        <td><?php echo $sub_row['topic_name']; ?></td>
                                        <td><?php echo $sub_row['total_right']; ?></td>
                                        <td><?php echo $sub_row['total_wrong']; ?></td>
                                    </tr>
                              <?php
                                  }
                                    mysqli_free_result($rs_topic_bu);

                              }
                              mysqli_free_result($rs_bu);
                            ?>
                        </table>

                    <?php
                      }

                      ?>



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
