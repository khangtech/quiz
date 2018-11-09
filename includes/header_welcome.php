<?php session_start() ; ?>
<?php include("includes/db.php") ?>
<?php include('includes/functions.php') ?>


<!DOCTYPE html>
<html lang="vn">
<head>
<title>GreenFeed Online Quiz</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- disallow browser cache -->
<meta HTTP-EQUIV="Pragma" content="no-cache">
<meta HTTP-EQUIV="Expires" content="-1" >

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<link href="css/style.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/jquery.countdown.css" rel="stylesheet">


<?php 



//neu chua dang nhap thi bat buoc phai dang nhap
if (!isset($_SESSION["user_info"]) || $_SESSION["user_info"]=='') {
    header("Location: login.php");
} 

?>

</head>
<body>
