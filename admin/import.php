<?php include("includes/admin_header.php"); ?>
<?php

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


if(isset($_FILES['list_sales'])){
if($_FILES['list_sales']['tmp_name']){

if(!$_FILES['list_sales']['error'] && $_POST["cboBU"]>0)
{
    $inputFile = $_FILES['list_sales']['tmp_name'];
    //$extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
    $extension = 'XLSX';
    if($extension == 'XLSX' || $extension == 'xls'){

        //Read spreadsheeet workbook
        try {
             $inputFileType = PHPExcel_IOFactory::identify($inputFile);
             $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                 $objPHPExcel = $objReader->load($inputFile);
        } catch(Exception $e) {
                die($e->getMessage());
        }

        //Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();


        $count_insert = 0;
        $count_update = 0;
        // disable all sales cua BU nay
        Disable_All_Sales($connection, $_POST["cboBU"]);

        //Loop through each row of the worksheet in turn, bo dong 1
        for ($row = 2; $row <= $highestRow; $row++){
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                //Insert into database
               
                $code = $rowData[0][0];
                $name = $rowData[0][1];
                //sub bu 
                $sub_bu = $rowData[0][2];
                $bu_id = $_POST["cboBU"];
                //neu khong ton tai moi insert
                if (!Check_Exist_Sales($connection,$code)) {
                  Create_Sales($connection, $code,$name,$bu_id,$sub_bu,1);
                  $count_insert +=1;
                } else {
                  Active_Sales_With_BU($connection, $code, $sub_bu,$bu_id);
                  $count_update +=1;
                }


        }


        $fileError = 'Thêm mới thành công ' . $count_insert . " mẩu tin. Cập nhật thành công " . $count_update . " mẩu tin";
       //header("Location: index.php");

    }
    else{
        $fileError = 'File không hợp lệ. Vui lòng nhập file excel';

    }
}
else{
    echo $_FILES['list_sales']['error'];
}
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
                        <h2 class="page-header">Import Sales</h2>
                        <form  action="import.php" method="post" enctype="multipart/form-data">
                          <div  class="form-group <?php echo !empty($fileError)?'error':'';?>">


                                 <label class="control-label" for="list_bu">Chọn BU cần import</label>

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

                                <br>


                                <label class="control-label" for="list_sales">File excel</label>

                                <input name="list_sales" class="form-control" type="file" />
                                <?php if (!empty($fileError)): ?>
                                    <span class="help-inline"><?php echo $fileError;?></span>
                                <?php endif; ?>
                            </div>


                          <div class="form-actions">
                              <button type="submit" class="btn btn-success">Import</button>
                              <a class="btn" href="index.php">Quay lại</a>
                            </div>


                        </form>
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
