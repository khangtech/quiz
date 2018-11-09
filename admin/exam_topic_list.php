<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>

        <?php
            if (isset($_GET['exam_id'])){
              $exam_id = mysqli_real_escape_string($connection, $_GET['exam_id']);
            }
        ?>


        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Chọn chủ đề cho cuộc thi</h2>
                        <div class="col-xs-7">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th width="180px">Chủ đề</th>
                                    <th>Số câu hỏi</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $rs_question = Load_All_Exam_Topic_By_Exam($connection,$exam_id);
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_question)) {

                                        $topic_id = $row["topic_id"];
                                        $topic_name =  $row["topic_name"];
                                        $number_questions =   $row["number_questions"];
                                        $exam_topic_id = $row["id"];
                                        echo "<tr><td>{$counter}</td>" ;
                                        echo "<td>{$topic_name}</td>" ;
                                        echo "<td>{$number_questions}</td>" ;
                                        echo "<td><a href='exam_topic_list.php?edit={$exam_topic_id}&exam_id={$exam_id}'>Sửa</a>&nbsp;|&nbsp;<a href='exam_topic_list.php?delete={$exam_topic_id}&exam_id={$exam_id}'>Xóa</a></td>";
                                        $counter+=1;
                                      }
                                  ?>
                              </tbody>
                          </table>
                          <?php // xu ly xoa
                             if (isset($_GET['delete'])){
                               $the_exam_topic_id = $_GET['delete'];
                               Delete_Exam_Topic($connection,$the_exam_topic_id,$exam_id);
                             }
                          ?>
                        </div>
                        <div class="col-xs-5">
                          <?php
                              Create_Update_Exam_Topic($connection);
                          ?>
                          <form action="" method="post">
                              <?php
                                  if (isset($_GET['edit'])) {
                                      $rs_exam_topic = Load_Exam_Topic($connection, $_GET['edit']);
                                      while ($row= mysqli_fetch_assoc($rs_exam_topic)){
                                          $questionNumber = $row["number_questions"];
                                          $topicID = $row["topic_id"];
                                          $the_edit_id = $row["id"];
                                      }
                                  }
                              ?>


                              <div class="form-group">
                                  <label for="txtTopic">Chủ đề</label>
                                  <select name="cboTopicID" class="form-control">
                                    <?php
                                        $rs_topic = Load_All_Topic($connection, $exam_id);
                                        while($row = mysqli_fetch_assoc($rs_topic)) {?>
                                          <option <?php echo ($topicID == $row["topic_id"] ? 'selected' : '');  ?> value="<?php echo $row["topic_id"]; ?>"><?php echo $row["topic_name"]; ?></option>
                                        <?php }
                                    ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                <label for="txtNumberQuestion">Số câu hỏi</label>
                                <input type="text" class="form-control" name="txtNumberQuestion" value="<?php echo $questionNumber;  ?>" />
                              </div>




                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success" value="Save" />
                                <a class="btn btn-danger" href="exam_list.php">Cancel</a>
                                <input type="hidden" name="txtExamTopicId" value="<?php echo $the_edit_id ?>">
                                <input type="hidden" name="txtExamID" value="<?php echo $exam_id ?>">
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
