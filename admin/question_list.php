<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Danh sách câu hỏi</h2>
                        <div class="col-xs-7">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Câu hỏi</th>
                                    <th width="180px">Thuộc chủ đề</th>
                                    <th>Kích hoạt</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $rs_question = Load_All_Question($connection);
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_question)) {
                                        $question_id = $row["question_id"];
                                        $question =   $row["question"];
                                        $tick_icon = $row["is_active"]==1 ? "X" :"";
                                        $topic_name = $row["topic_name"];

                                        echo "<tr><td>{$counter}</td>" ;
                                        echo "<td><a href='question_list.php?edit={$question_id}'>{$question}</a></td>" ;
                                        echo "<td>{$topic_name}</td>" ;
                                        echo "<td>{$tick_icon}</td>" ;
                                        echo "<td><a href='question_list.php?edit={$question_id}'>Sửa</a>&nbsp;|&nbsp;<a href='question_list.php?delete={$question_id}'>Xóa</a>&nbsp;<a class='btn btn-info' href='answer_list.php?id={$question_id}'>Các đáp án</a></td>";
                                        $counter+=1;
                                      }
                                  ?>
                              </tbody>
                          </table>
                          <?php // xu ly xoa
                             if (isset($_GET['delete'])){
                               $the_question_id = $_GET['delete'];
                               Delete_Question($connection,$the_question_id);
                             }
                          ?>
                        </div>
                        <div class="col-xs-5">
                          <?php
                              Create_Update_Question($connection);
                          ?>
                          <form action="" method="post" enctype="multipart/form-data">
                              <?php
                                  if (isset($_GET['edit'])) {
                                      $rs_question = Load_Question($connection, $_GET['edit']);
                                      while ($row= mysqli_fetch_assoc($rs_question)){
                                          $questionEditName = $row["question"];
                                          $questionEditActive = $row["is_active"]==1 ? 'checked' : '';
                                          $topicID = $row["topic_id"];
                                          $the_edit_id = $row["question_id"];
                                          $question_image = $row["question_image"];

                                      }
                                  }
                              ?>
                              <div class="form-group">
                                <label for="txtQuestionName">Câu hỏi</label>
                                <input type="text" class="form-control" name="txtQuestion" value="<?php echo $questionEditName;  ?>" />
                              </div>


                              <div class="form-group">
                                <label for="txtQuestionImage">Hình minh họa</label>
                                <input type="file" class="form-control" 
                                name="txtQuestionImage" />
                              </div>

                              <div class="form-group">
                                <label for="txtQuestionImage"></label>
                                <?php 
                                    if ($question_image !='') {
                                      ?>
                                      <img width="400px" src="../images/<?php echo  $question_image;  ?>" />
                                <?php       
                                    }
                                ?>
                              </div>

                              <div class="form-group">
                                  <label for="txtTopic">Thuộc chủ đề</label>
                                  <select name="cboTopic" class="form-control">
                                    <?php
                                        $rs_topic = Load_All_Topic($connection);
                                        while($row = mysqli_fetch_assoc($rs_topic)) {?>
                                          <option <?php echo ($topicID == $row["topic_id"] ? 'selected' : '');  ?> value="<?php echo $row["topic_id"]; ?>"><?php echo $row["topic_name"]; ?></option>
                                        <?php }
                                    ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label class="checkbox-inline"><input type="checkbox" name="chkQuestionActive" <?php echo $questionEditActive;  ?> value="1">Kích hoạt</label>
                              </div>


                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success" value="Save" />
                                <a class="btn btn-danger" href="question_list.php">Cancel</a>
                                <input type="hidden" name="txtQuestionId" value="<?php echo $the_edit_id ?>">
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
