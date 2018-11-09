<?php include("includes/admin_header.php"); ?>

<?php

$bu_id = $_SESSION['admin_info']['bu_id'];


// neu la BOSS 
if ($_SESSION['admin_info']['id'] == 1) {
  if (isset($_POST['txtSaleCode']) &&  $_POST['txtSaleCode'] != ""){
    if (isset($_POST['cboBU']) && $_POST['cboBU'] > 0) { // tim theo sales va BU
       $sale_code = mysqli_real_escape_string($connection, $_POST['txtSaleCode']);
       $bu_search = mysqli_real_escape_string($connection, $_POST['cboBU']);
       $rs_sales = Find_Sales($connection, $sale_code, $bu_search);
    } else { // tim theo ma nhan vien
       $sale_code = mysqli_real_escape_string($connection, $_POST['txtSaleCode']);
       $rs_sales = Find_Sales_SuperAdmin($connection, $sale_code);
    }
  } else {
    if (isset($_POST['cboBU']) && $_POST['cboBU'] > 0) { // tim theo  BU
       $bu_search = mysqli_real_escape_string($connection, $_POST['cboBU']);
       $rs_sales = Find_BUSales_SuperAdmin($connection, $bu_search);
    } else { // tim het 
       $rs_sales = Load_All_Sales($connection);   
    }
  }
} 
// neu la ADMIN_BU
else {
  if (isset($_POST['txtSaleCode']) &&  $_POST['txtSaleCode'] != ""){
    $sale_code = mysqli_real_escape_string($connection, $_POST['txtSaleCode']);
    $rs_sales = Find_Sales($connection, $sale_code, $bu_id);
  } else {
    $rs_sales = Load_Sales($connection, $bu_id);
  }
}





?>



    <div id="wrapper">
        <?php include("includes/admin_nav.php"); // nav menu ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">Danh sách nhân viên</h2>
                        <div class="col-xs-12">
  <form action="" method="post">
                          <div class="form-group">
                            <label for="txtSaleCode">Mã nhân viên</label>
                            <input type="text" class="form-control" name="txtSaleCode" />
                          </div>

                          <?php if ($_SESSION['admin_info']['id'] == 1) { ?>
                          <div class="form-group">
                            <label for="cboBU">BU</label>
                             <select name="cboBU" class="form-control">
                                <option value="0">Vui lòng chọn BU </option>
                                <?php
                                    $rs_bu = Load_All_BU($connection);
                                    while ($row = mysqli_fetch_assoc($rs_bu)) {
                                      $bu_id = $row["bu_id"];
                                      $bu_name =$row["bu_name"]; ?>
                                      <option value="<?php echo $bu_id?>"><?php echo $bu_name ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                          </div>

                          <?php } ?>


                          <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-success" value="Tìm nhân viên" />

                            <a  class="btn btn-info" href="sales_list.php">Hiện tất cả</a>

                          </div>

</form>


                          <table class="table table-bordered table-hover">


                              <thead>
                                  <tr>
                                    <th>Mã NV</th>
                                    <th>Họ tên</th>
                                    <th>BU</th>
                                    <th>BU - sub</th>
                                    <th>Kích hoạt</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php

                                      $counter = 0;
                                      while ($row = mysqli_fetch_assoc($rs_sales)) {
                                        $sale_code = $row["sale_code"];
                                        $sale_name =   $row["sale_name"];
                                        $bu_name =   $row["bu_name"];
                                        $bu_sub_name =   $row["bu_sub"];
                                        $tick_icon = $row["is_active"]==1 ? "X" :"";

                                        echo "<tr><td>{$sale_code}</td>" ;
                                        echo "<td>{$sale_name}</td>" ;
                                        echo "<td>{$bu_name}</td>" ;
                                        echo "<td>{$bu_sub_name}</td>" ;
                                        echo "<td>{$tick_icon}</td>" ;
                                        echo "<td><a href='sales.php?active={$sale_code}'>Disable</a></td>";
                                        $counter+=1;
                                      }
                                  ?>
                              </tbody>

                              <tfoot>
                                <tr>
                                    <td colspan="5">
                                      Tổng cộng: <strong><?php echo $counter;  ?></strong>
                                    </td>
                                </tr>
                                  
                                                                </tfoot>
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
