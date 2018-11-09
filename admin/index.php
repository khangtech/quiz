<?php include ("includes/admin_header.php"); ?>
    <div id="wrapper">





        <?php include("includes/admin_nav.php") ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to Admin

                        </h1>

                        <div class="col-xs-6">
                          <form action="">
                              <div class="form-group">
                                <label for="txtExamTitle">Tên cuộc thi</label>
                                <input  type="text" class="form-control" name="txtExamTitle" />
                              </div>


                              <div class="form-group">
                                <label for="txtExamTitle">Ngày tổ chức</label>
                                <input  type="date" class="form-control" name="txtExamDate" />
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
