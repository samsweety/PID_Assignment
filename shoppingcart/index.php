<?php
    session_start();
     
    if(isset($_POST["btnOK"])){
      $current="id".$_POST["gid"];
      if(!is_numeric($_POST["amount"])){
        echo "輸入的值並非數字";
      }else if($_POST["amount"]<0){
        echo "輸入的值小於零";
      }else{
        $_SESSION[$current]+=$_POST["amount"];}
    }      
 
?>

<?php
  $link=mysqli_connect("localhost","root","root","shoppingMall");
  mysqli_query($link,"set names utf-8");
  $sql=<<<sql
      select * from goods;
      sql;
  $result=mysqli_query($link,$sql);
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
    <style>
      #cart{
        position:fixed;
        bottom:50px;
        right:50px;
        width:50px;
      }
    
    </style>
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
    <?php if($_SESSION["power"]<0){?>
        <a class="nav-link" style="color:yellow" href="manage.php">管理</a>
    <?php } ?>
    <?php if($_SESSION["power"]>0){?>
        <a class="nav-link" style="color:yellow" href="member.php">會員中心</a>
    <?php } ?>
  </ul>
</nav>

<div class="container">
  <h2>商品目錄</h2>
  <p>本店保持著童叟無欺 品質保證的情況下經營多年</p>            
  <table class="table table-dark table-hover">
    <thead>
      <tr>
        <th>品名</th>
        <th>價格</th>
        <th>數量</th>
        <th></th>
      </tr>
    </thead>
    
    <tbody>
      <?php for(;$row=mysqli_fetch_assoc($result);) {?>
      <form method="post">
        <tr>
          <td><?= $row["goodsName"]?></td>
          <td><?= $row["goodsPrice"]?></td>
          <td><input type="text" name="amount" id="amount"></td>
          <td><input type="submit" name="btnOK" id="btnOK" class="btn-success" value="加入購物車">
          <input type="hidden" name="gid" value="<?=$row["gid"]?>"></td>
        </tr>      
      </form>
      <?php } ?>
    </tbody>
    
  </table>
</div>  
        <?php if($_SESSION["power"]>0){?>
          <div id="cart"><a href="cart.php"><img src="image/cart.png" width="50px" height="50px"></a></div>
        <?php }?>
</body>
</html>
