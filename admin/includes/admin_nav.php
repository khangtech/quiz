<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="sales_list.php">CMS Quiz Application</a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">


        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Xin chào: <?php echo $_SESSION["admin_info"]['account'] ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <?php if ($_SESSION['admin_info']['id'] == 1) { ?> 

                          <a href="super_changepassword.php"><i class="fa fa-fw fa-user"></i>Đổi mật khẩu</a>   

                     <?php } else { ?>
                        <a href="user_changepassword.php"><i class="fa fa-fw fa-user"></i>Đổi mật khẩu</a>

                    <?php    
                     } ?>   
                   
                </li>

                <li class="divider"></li>
                <li>
                    <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">

          <?php
            if ($_SESSION['admin_info']['id'] == 1)  { ?>

              <li>
                  <a href="question_list.php"><i class="fa fa-fw fa-table"></i>Ngân hàng câu hỏi</a>
              </li>

              <li>
                  <a href="topic_list.php"><i class="fa fa-fw fa-dashboard"></i>Các chủ đề</a>
              </li>

                <li>
                    <a href="exam_list.php"><i class="fa fa-fw fa-dashboard"></i>Tổ chức thi</a>
                </li>



                <li>
                    <a href="import.php"><i class="fa fa-fw fa-dashboard"></i>Import</a>
                </li>

                <li>
                    <a href="admin_list.php"><i class="fa fa-fw fa-edit"></i>Người dùng</a>
                </li>


          <?php
            }
          ?>



            <li>
                <a href="sales_list.php"><i class="fa fa-fw fa-dashboard"></i>Danh sách nhân viên</a>
            </li>
            <li>
                <a href="report.php"><i class="fa fa-fw fa-bar-chart-o"></i>Bảng thống kê tổng quan</a>
            </li>

            <li>
                <a href="report_hard.php"><i class="fa fa-fw fa-bar-chart-o"></i>Câu hỏi thường làm sai</a>
            </li>

<!--
            <li>
                <a href="charts.php"><i class="fa fa-fw fa-bar-chart-o"></i>Bảng thống kê</a>
            </li>

            <li>
                <a href="users.html"><i class="fa fa-fw fa-edit"></i>Người dùng</a>
            </li>-->

        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>
