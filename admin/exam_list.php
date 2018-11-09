<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Danh sách cuộc thi</h2>
                        <div class="col-xs-8">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Ngày thi</th>
                                    <th>BU tham gia</th>
                                    <th>Ghi chú</th>
                                    <th>Câu hỏi</th>
                                    <th>Kích hoạt</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $rs_exam = Load_All_Exam($connection);
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_exam)) {
                                        $exam_id = $row["exam_id"];
                                        $exam_int = strtotime($row["exam_date"]);
                                        $exam_date = date("d-m-Y",$exam_int);
                                        $exam_desc = $row["exam_desc"];
                                        $exam_total = $row["exam_total"];
                                        $tick_icon = $row["exam_active"]==1 ? "X" :"";
                                        $bu_name =   $row["bu_name"];

                                        echo "<tr><td>{$counter}</td>" ;
                                        echo "<td><a href='exam_list.php?edit={$exam_id}'>{$exam_date}</a></td>" ;
                                        echo "<td>{$bu_name}</td>" ;
                                        echo "<td>{$exam_desc}</td>" ;
                                        echo "<td>{$exam_total}</td>" ;
                                        echo "<td>{$tick_icon}</td>" ;
                                        echo "<td><a href='exam_list.php?edit={$exam_id}'>Sửa</a>&nbsp;|&nbsp;<a href='exam_list.php?delete={$exam_id}'>Xóa</a>|&nbsp;<a class='btn btn-info' href='exam_topic_list.php?exam_id={$exam_id}'>Topic </a></td>";
                                        $counter+=1;
                                      }
                                  ?>
                              </tbody>
                          </table>
                          <?php // xu ly xoa
                             if (isset($_GET['delete'])){
                               $the_exam_id = $_GET['delete'];
                               Delete_Exam($connection,$the_exam_id);
                             }
                          ?>
                        </div>
                        <div class="col-xs-4">
                          <?php
                              Create_Update_Exam($connection);
                          ?>
                          <form action="" method="post" >
                              <?php
                                  if (isset($_GET['edit'])) {
                                      $rs_exam = Load_Exam($connection, $_GET['edit']);
                                      while ($row= mysqli_fetch_assoc($rs_exam)){
                                          $examEditDate = $row["exam_date"];
                                          $examEditActive = $row["exam_active"]==1 ? 'checked' : '';
                                          $examEditBU = $row["bu_id"];
                                          $examDesc =  $row["exam_desc"];
                                          $examDuration = $row["exam_duration"];
                                          $examPassed = $row["exam_passed"];  
                                          $examTotal = $row["exam_total"];
                                          $the_edit_id = $row["exam_id"];
                                      }
                                  }
                              ?>
                              <div class="form-group">
                                <label for="txtExamDate">Ngày thi</label>
                                <input type="date" class="form-control" name="txtExamDate" value="<?php echo $examEditDate;  ?>" />
                              </div>


                               <div class="form-group">
                                <label for="txtExamDesc">Ghi chú</label>
                                <input type="text" class="form-control" name="txtExamDesc" value="<?php echo $examDesc;  ?>">
                              </div>


                              <div class="form-group">
                                <label for="txtExamDesc">Thời gian (phút)</label>
                                <input type="text" class="form-control" name="txtExamDuration" value="<?php echo $examDuration;  ?>">
                              </div>


                               <div class="form-group">
                                <label for="txtExamDesc">Điểm đậu (%)</label>
                                <input type="text" class="form-control" name="txtExamPassed" value="<?php echo $examPassed;  ?>">
                              </div>  

                                <div class="form-group">
                                <label for="txtExamDesc">Số câu hỏi</label>
                                <input type="text" class="form-control" name="txtExamTotal" value="<?php echo $examTotal;  ?>">
                              </div>   

                              <div class="form-group">
                                  <label for="cboBUId">BU tham gia</label>
                                  <select name="cboBUId" class="form-control">
                                    <?php
                                        $rs_bu = Load_All_BU($connection);
                                        while($row = mysqli_fetch_assoc($rs_bu)) {?>
                                          <option <?php echo ($examEditBU == $row["bu_id"] ? 'selected' : '');  ?> value="<?php echo $row["bu_id"]; ?>"><?php echo $row["bu_name"]; ?></option>
                                        <?php }
                                    ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label class="checkbox-inline"><input type="checkbox" name="chkExamActive" <?php echo $examEditActive;  ?> value="1">Kích hoạt</label>
                              </div>

                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success" value="Save" />
                                <a class="btn btn-danger" href="exam_list.php">Cancel</a>
                                <input type="hidden" name="txtExamId" value="<?php echo $the_edit_id ?>">
                              </div>
                          </form>
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
