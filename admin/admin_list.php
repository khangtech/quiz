<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Danh sách các admin</h2>
                        <div class="col-xs-7">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th width="180px">Tên truy cập</th>
                                    <th>BU quản lý</th>
                                    <th>Kích hoạt</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                      $rs_user = Load_All_AdminUser($connection);
                                      $counter = 1;
                                      while ($row = mysqli_fetch_assoc($rs_user)) {
                                        $id = $row["id"];
                                        $account =  $row["account"];
                                        $bu_name =   $row["bu_name"];
                                        $tick_icon = $row["active"]==1 ? "X" :"";
                                        echo "<tr><td>{$counter}</td>" ;
                                        echo "<td>{$account}</td>" ;
                                        echo "<td>{$bu_name}</td>" ;
                                        echo "<td>{$tick_icon}</td>" ;
                                        echo "<td><a href='admin_list.php?edit={$id}'>Sửa</a>&nbsp;|&nbsp;<a href='admin_list.php?delete={$id}'>Xóa</a></td>";
                                        $counter+=1;
                                      }
                                  ?>
                              </tbody>
                          </table>
                          <?php // xu ly xoa
                             if (isset($_GET['delete'])){
                               $_id = $_GET['delete'];
                               Delete_AdminUser($connection,$_id);
                             }
                          ?>
                        </div>
                        <div class="col-xs-5">
                          <?php
                              Create_Update_AdminUser($connection);
                          ?>
                          <form action="" method="post">
                              <?php
                                  if (isset($_GET['edit'])) {
                                      $rs_user = Load_AdminUser($connection, $_GET['edit']);
                                      while ($row= mysqli_fetch_assoc($rs_user)){
                                          $txtaccount = $row["account"];
                                          $chkActive = $row["active"]==1 ? 'checked' : '';
                                          $cboBUId = $row["bu_id"];
                                          $the_edit_id = $row["id"];
                                      }
                                  }
                              ?>


                              <div class="form-group">
                                  <label for="txtTopic">BU quản lý</label>
                                  <select name="cboBU" class="form-control">
                                    <?php
                                        $rs_bu = Load_All_BU($connection, $exam_id);
                                        while($row = mysqli_fetch_assoc($rs_bu)) {?>
                                          <option <?php echo ($cboBUId == $row["bu_id"] ? 'selected' : '');  ?> value="<?php echo $row["bu_id"]; ?>"><?php echo $row["bu_name"]; ?></option>
                                        <?php }
                                    ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                <label for="txtAdminAccount">Tên truy cập</label>
                                <input type="text" class="form-control" name="txtAdminAccount" value="<?php echo $txtaccount;  ?>" />
                              </div>

                              <?php if ($the_edit_id > 0 ){  ?>
                                    <div class="form-group">
                                    <label for="txtResetPassword">Reset mật khẩu</label>
                                    <input type="password" class="form-control" name="txtResetPassword" value="" />
                                  </div>
                              <?php } 

                                else {?>
                                  <div class="form-group">
                                    <label for="txtAdminAccount">Mật khẩu</label>
                                    <input type="password" class="form-control" name="txtAdminPassword" value="<?php echo $txtpassword;  ?>" />
                                  </div>
                              <?php
                                }
                              ?>






                              <div class="form-group">
                                  <label class="checkbox-inline"><input type="checkbox" name="chkAdminActive" <?php echo $chkActive;  ?> value="1">Kích hoạt</label>
                              </div>


                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success" value="Save" />
                                <a class="btn btn-danger" href="admin_list.php">Cancel</a>
                                <input type="hidden" name="txtAdminId" value="<?php echo $the_edit_id ?>">
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
