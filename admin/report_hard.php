<?php include("includes/admin_header.php"); ?>





    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Thống kê câu hỏi hay sai</h2>
                        <div class="col-xs-12">
                      

                          <table class="table table-bordered table-hover">
                          
                            <tr>
                            <th>Câu hỏi</td>
                          <th>Chủ đề</th>
                          <th>Số câu sai</th>
                            </tr>

                            <?php
                                $rs_exams = Load_Most_Wrong($connection);
                                while ($row = mysqli_fetch_assoc($rs_exams)) { ?>   
                                    <tr>
                                    <td width="800px"><?php echo $row["question"]; ?></td>
                                    <td><?php echo $row["topic_name"]; ?></td>
                                    <td><?php echo $row["wrong_answer"]; ?></td>
                                    </tr>
                            <?php         
                                }
                            ?>
                            
                            <?php 
                                 mysqli_free_result($rs_exams);
                            ?>
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
