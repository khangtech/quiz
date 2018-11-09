<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        
                    
                        <div class="col-xs-12">
                         
                          <form action="" method="post" enctype="multipart/form-data">
                              <?php
                                  if (isset($_POST['submit'])) {
                                     $old_pass = $_POST["txtOldPass"];
                                     $new_pass = $_POST["txtNewPass"];
                                     $new_pass_confirm = $_POST["txtConfirmPass"];

                                     $user_id =  $_SESSION["admin_info"]["id"];

                                     $test_old_pass = checkOldPassword($connection, $user_id, $old_pass);
                                     if ($test_old_pass) {
                                        if ($new_pass == $new_pass_confirm) {
                                            if (strlen($new_pass)<8) {
                                               echo "Mật khẩu nên dài hơn 8 ký tự";  
                                            } else {
                                              changePassword($connection, $user_id, $new_pass);
                                              echo "Đổi mật khẩu thành công!";
                                            }
                                            
                                        } else {
                                            echo "Mật khẩu mới không khớp!";
                                        }
                                        
                                     } else {
                                        echo "Mật khẩu cũ không hợp lệ!";
                                     }
                                  }
                              ?>
                              <div class="form-group">
                                <label for="txtOldPass">Mật khẩu cũ</label>
                                <input type="password" class="form-control" name="txtOldPass" value="" />
                              </div>

                              <div class="form-group">
                                <label for="txtNewPass">Mật khẩu mới</label>
                                <input type="password" class="form-control" name="txtNewPass" value="" />
                              </div>
                             

                               <div class="form-group">
                                <label for="txtConfirmPass">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" name="txtConfirmPass" value="" />
                              </div>
                             
                            
                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success" value="Save" />
                                <a class="btn btn-danger" href="report.php">Cancel</a>
                               
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
