<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>

        <?php
            if (isset($_GET['id'])){
              $question_id = mysqli_real_escape_string($connection, $_GET['id']);
            }
        ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                              $rs_question = Load_Question($connection, $question_id);
                              while($row = mysqli_fetch_assoc($rs_question)) {
                                $question = $row["question"];
                              }
                        ?>
                        <h1 class="page-header">
                            <?php echo $question  ?>
                        </h1>
                        <div class="col-xs-7">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Câu trả lời</th>
                                    <th>Hình</th>
                                    <th>Đáp án</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $rs_answer = Load_All_Answer($connection, $question_id);
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_answer)) {
                                        $choice_id = $row["choice_id"];
                                        $choice =   $row["choice"];
                                        $tick_icon = $row["is_right_choice"]==1 ? "X" :"";
                                        $photo =  $row["choice_image"];
                                        echo "<tr><td>{$counter}</td>" ;
                                        echo "<td><a href='answer_list.php?edit={$choice_id}&id={$question_id}'>{$choice}</a></td>" ;
                                         echo "<td>" . ($photo !="" ? '<img width="180px" src="../images/' . $photo . '">' : "") .   "</td>" ;
                                        echo "<td>{$tick_icon}</td>" ;
                                        echo "<td><a href='answer_list.php?edit={$choice_id}&id={$question_id}'>Sửa</a>&nbsp;|&nbsp;<a href='answer_list.php?delete={$choice_id}&id={$question_id}'>Xóa</a></td>";
                                        $counter+=1;
                                      }
                                  ?>
                              </tbody>
                              <tfoot>
                                  <tr>
                                      <td colspan="4">
                                          <a class='btn btn-info' href='question_list.php'>Danh sách câu hỏi</a>
                                      </td>
                                  </tr>
                              </foot>
                          </table>
                          <?php // xu ly xoa
                             if (isset($_GET['delete'])){
                               $the_choice_id = $_GET['delete'];
                               Delete_Answer($connection,$the_choice_id,$question_id);
                             }
                          ?>
                        </div>
                        <div class="col-xs-5">
                          <?php
                              Create_Update_Answer($connection, $question_id);
                          ?>
                          <form action="" method="post" enctype="multipart/form-data">
                              <?php
                                  if (isset($_GET['edit'])) {
                                      $rs_choice = Load_Answer($connection, $_GET['edit']);
                                      while ($row= mysqli_fetch_assoc($rs_choice)){
                                          $choiceEditName = $row["choice"];
                                          $choiceEditRight = $row["is_right_choice"]==1 ? 'checked' : '';
                                          $the_edit_id = $row["choice_id"];
                                          $choice_image = $row["choice_image"];
                                      }
                                  }
                              ?>
                              <div class="form-group">
                                <label for="txtExamTitle">Câu trả lời</label>
                                <input type="text" class="form-control" name="txtChoice" value="<?php echo $choiceEditName;  ?>" />
                              </div>
                               <div class="form-group">
                                <label for="txtChoiceImage">Hình chọn</label>
                                <input type="file" class="form-control" 
                                name="txtChoiceImage" />
                              </div>

                              <div class="form-group">
                                <label for="txtChoiceImage"></label>
                                <?php 
                                    if ($choice_image !='') {
                                      ?>
                                      <img width="400px" src="../images/<?php echo  $choice_image;  ?>" />
                                <?php       
                                    } 
                                ?>
                              </div>



                              <div class="form-group">
                                  <label class="checkbox-inline"><input type="checkbox" name="chkRightChoice" <?php echo $choiceEditRight;  ?> value="1">Câu trả lời đúng</label>
                              </div>
                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success" value="Save" />
                                <a class="btn btn-danger" href="answer_list.php?id=<?php echo $question_id?>">Cancel</a>
                                <input type="hidden" name="txtChoiceID" value="<?php echo $the_edit_id ?>">
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
