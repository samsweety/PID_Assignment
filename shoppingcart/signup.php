<?php
    session_start();
    if(isset($_SESSION["userName"])){
        header("location:index.php");
        exit();
    }
    if(isset($_POST["home"])){
        header("location:index.php");
        exit();
    }
    if(isset($_POST["login"])){
        header("location:login.php");
        exit();
    }
    

?>

<?php
  $link=mysqli_connect("localhost","sam","55688","shoppingMall");
  mysqli_query($link,"set names utf-8");
  if(isset($_POST["signup"])){
    if(empty($_POST["txtUserName"])||empty($_POST["txtPassWord"])){
        echo "帳號密碼不能為空值";
    }
    else{
        $userName=$_POST["txtUserName"];
        $pw=$_POST["txtPassWord"];

        $sql=<<<sql
                insert into users (userName,pw,power) values ("$userName","$pw",1);
            sql;
        mysqli_query($link,$sql);
        header("location:login.php");
        exit();
    
    }

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>隨便亂買購物網</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</head>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand/logo -->
  <a class="navbar-brand" href="index.php">亂買</a>
  
  <!-- Links -->
  <ul class="navbar-nav mr-auto">
    <li class="nav-item">
      <?php if(!isset($_SESSION["userName"])){?>
        <a class="nav-link" style="color:red" href="login.php">登入</a>
      <?php }else{?>
        <a class="nav-link" style="color:red" href="login.php?logout=1">登出</a>
      <?php }?>
    </li>
    <span class="navbar-text">
        當前使用者： <?= (isset($_SESSION["userName"])?$_SESSION["userName"]:"訪客" )?>
    </span>
    
  </ul>
</nav>

<div class="container">           
  <table class="table table-dark table-hover">
    <thead>
      <form method="post">
      <tr align="center">
        <th>帳號</th>
        <th>密碼</th>
        <th></th>
      </tr>
      <tr align="center">
        <th><input type="text" name="txtUserName" id="txtUserName"></th>
        <th><input type="password" name="txtPassWord" id="txtPassWord"></th>
        <th><input type="submit" class="btn-primary" name="login" id="login" value="已有帳號？ 登入"></th>
      </tr>
      <tr align="center">
        <th><input type="submit" class="btn-outline-primary" name="signup" id="signup" value="註冊"></th>
        <th><input type="submit" class="btn-outline-danger"  name="home" id="home" value="回首頁"></th>
        <th></th>
      </tr>
      </form>
    </thead>    
  </table>
</div>
        
</body>
</html>
