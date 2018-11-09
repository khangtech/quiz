<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Danh sách chủ đề</h2>
                        <div class="col-xs-7">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Tên chủ đề</th>
                                    <th>Số lượng câu hỏi</th>
                                    <th>Kích hoạt</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $rs_topic = Load_All_Topic_With_Count($connection);
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_topic)) {
                                        $topic_id = $row["topic_id"];
                                        $topic_name =   $row["topic_name"];
                                        $tick_icon = $row["topic_active"]==1 ? "X" :"";
                                        $total_count = $row["total"];
                                        echo "<tr><td>{$counter}</td>" ;
                                        echo "<td><a href='topic_list.php?edit={$topic_id}'>{$topic_name}</a></td>" ;
                                        echo "<td>{$total_count}</td>" ;
                                        echo "<td>{$tick_icon}</td>" ;
                                        echo "<td><a href='topic_list.php?edit={$topic_id}'>Sửa</a>&nbsp;|&nbsp;<a href='topic_list.php?delete={$topic_id}'>Xóa</a></td>";
                                        $counter+=1;
                                      }
                                  ?>
                              </tbody>
                          </table>
                          <?php // xu ly xoa
                             if (isset($_GET['delete'])){
                               $the_topic_id = $_GET['delete'];
                               Delete_Topic($connection,$the_topic_id);
                             }
                          ?>
                        </div>
                        <div class="col-xs-5">
                          <?php
                              Create_Update_Topic($connection);
                          ?>
                          <form action="" method="post">
                              <?php
                                  if (isset($_GET['edit'])) {
                                      $rs_topic = Load_Topic($connection, $_GET['edit']);
                                      while ($row= mysqli_fetch_assoc($rs_topic)){
                                          $topicEditName = $row["topic_name"];
                                          $topicEditActive = $row["topic_active"]==1 ? 'checked' : '';
                                          $the_edit_id = $row["topic_id"];
                                      }
                                  }
                              ?>
                              <div class="form-group">
                                <label for="txtExamTitle">Tên chủ đề</label>
                                <input type="text" class="form-control" name="txtTopicName" value="<?php echo $topicEditName;  ?>" />
                              </div>
                              <div class="form-group">
                                  <label class="checkbox-inline"><input type="checkbox" name="chkTopicActive" <?php echo $topicEditActive;  ?> value="1">Kích hoạt</label>
                              </div>
                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success" value="Save" />
                                <a class="btn btn-danger" href="topic_list.php">Cancel</a>
                                <input type="hidden" name="txtTopicId" value="<?php echo $the_edit_id ?>">
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
