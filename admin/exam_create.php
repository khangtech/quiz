<?php include("includes/admin_header.php"); ?>
    <div id="wrapper">

        <?php include("includes/admin_nav.php") ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Tổ chức thi

                        </h1>

                        <div class="col-xs-6">
                          <form action="">
                              <div class="form-group">
                                <label for="txtExamTitle">Tên cuộc thi</label>
                                <input  type="text" class="form-control" name="txtExamTitle" />
                              </div>


                              <div class="form-group">
                                <label for="txtExamDate">Ngày tổ chức</label>
                                <input  type="date" class="form-control" name="txtExamDate" />
                              </div>


                              <div class="form-group">
                                <label for="txtExamTitle">Ghi chú</label>
                                <input  type="text" class="form-control" name="txtExamDesc" />
                              </div>


                              <div class="form-group">
                                <label for="txtExamDate">BU tổ chức</label>
                                <select name="cboBu" class="form-control">
                                  <option value="-1">--- chọn BU---</option>
                                  <?php
                                      $query_bu = "SELECT * FROM BU";
                                      $rs_bu = mysqli_query($connection,$query_bu);
                                      while ($bu_row = mysqli_fetch_assoc($rs_bu)) {
                                        echo "<option value=" . $bu_row["bu_id"] . ">" . $bu_row["bu_name"] . "</option>";
                                      }
                                  ?>
                                </select>
                              </div>

                              <div class="form-group">
                                  <label class="checkbox-inline"><input type="checkbox" name="chkActive" value="">Kích hoạt</label>
                              </div>

                              <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-primary btn-md" value="Save" />
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
