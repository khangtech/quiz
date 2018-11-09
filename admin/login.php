<?php session_start() ; ?>
<?php include("../includes/db.php") ?>
<?php include('../includes/functions.php') ?>
<?php
  if (isset($_POST["btnSubmit"])) {
    if (isset($_POST["txtAccountName"]) && !empty($_POST["txtAccountName"])) {
      $account = mysqli_real_escape_string($connection,$_POST["txtAccountName"]);
      if (isset($_POST["txtPassword"]) && !empty($_POST["txtPassword"]) ) {
        $password = mysqli_real_escape_string($connection,$_POST["txtPassword"]);
        //day du data, kiem tra data co trong db khong
        $rs_info = checkAdminLogin($connection,$account, $password);
        $countRow = mysqli_num_rows($rs_info);
        if ($countRow==0) {
          $err_msg = "Tài khoản không hợp lệ";
        } else {
          // luu lai thong tin cua user nay trong session
          while ($row = mysqli_fetch_assoc($rs_info)) {
            $_SESSION["admin_info"] = $row;
          //  print_r(  $_SESSION["admin_info"] );
          }
          header("Location: report.php");
        }

      } else {
        $err_msg = "Vui lòng nhập mật khẩu!";
      }
    } else {
        $err_msg = "Vui lòng nhập tên đăng nhập!";
    }
  }

?>
<!DOCTYPE html>
<html lang="vn">
<head>
<title>Green Feed</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/style.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body class="login">
<section class="loginBox">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <h1><a href="#"><img width="180px" src="../images/logo.png" alt="" /></a></h1>

        <div class="loginWrapper">
          <h2>Dành cho quản trị</h2>
        <div class="loginBoxInner">

          <form action="" method="post">
            <ul>
              <li>Vui lòng nhập tên đăng nhập và mật khẩu</li>
              <li>
                <input type="text" id="txtAccountName" name="txtAccountName" value="<?php echo $account;  ?>" placeholder="Tên đăng nhập" autocomplete="off" class="user2" />
              </li>

              <li>
                <input type="password" id="txtPassword" name="txtPassword" value="<?php echo $password;  ?>" placeholder="Mật khẩu" autocomplete="off" class="user2" />
              </li>

            </ul>

            <?php if ($err_msg!="") {
                        echo "<h3>" . $err_msg . "</h3>";
                  }
            ?>

            <p class="submitBtn">
              <input type="submit" name="btnSubmit" value="Đăng nhập" class="submit" />
            </p>
          </form>
        </div>

      </div>
      </div>
    </div>
  </div>
  <p class="copyright">Copyright ©<?php echo date("Y") ?> GreenFeed VietNam. All rights reserved</p>
</section>
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

</body>
</html>
