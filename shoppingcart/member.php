<?php
    session_start();
    $userName=$_SESSION["userName"];
    $uid=$_SESSION["uid"];
    $power=$_SESSION["power"];
    if(!isset($_SESSION["power"])){
      header("location:index.php");
      exit();
  }else if($_SESSION["power"]<0){
      header("location:index.php");
      exit();
  }
    
?>

<?php
  $link=mysqli_connect("localhost","sam","55688","shoppingMall");
  mysqli_query($link,"set names utf-8");
    if(isset($_POST["ongoing"])){
        $sql=<<<sql
            select bid from book where uid=$uid && cond=0;
        sql;
        $result=mysqli_query($link,$sql);

    }
    if(isset($_POST["history"])){
        $sql=<<<sql
            select bid from book where uid=$uid && cond=1;
        sql;
        $result=mysqli_query($link,$sql);

    }
    if(isset($_POST["deleteB"])){
      $bid=$_POST["bid"];
      $sql=<<<sql
        delete from book where bid=$bid;
        sql;
      mysqli_query($link,$sql);
    }


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>亂買會員中心</title>
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
      #center{
        text-align:center;
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
  <h2>功能</h2>       
  <table class="table table-dark table-hover" >
    <thead>
      <tr>
        <form method="post"><th id="center"><input  type="submit" name="ongoing" id="ongoing" value="進行中訂單"></th></form>
        <form method="post"><th id="center"><input  type="submit" name="history" id="history" value="歷史訂單"></th></form>
      </tr>
      <tr>
        <td>訂單編號</td>
        <td>商品名稱</td>
        <td>數量</td>
        <td>金額</td>

      </tr>
    </thead>
    <?php if(isset($result)){ ?>
    <tbody>
        <?php for(;$row=mysqli_fetch_assoc($result);){ 
            $bid=$row["bid"];
            ?>   
            <?php $sqllist=<<<sql
                select b.bid,g.goodsName,bd.amount,sum(bd.amount*g.goodsPrice) as total from users as u
                inner join book as b on u.uid=b.uid
                inner join bookDetail as bd on b.bid=bd.bid
                inner join goods as g on bd.gid=g.gid
                where u.uid=$uid&&b.bid=$bid
                group by b.bid,g.goodsName,bd.amount with rollup
                sql;
                $solve=mysqli_query($link,$sqllist);

                for($i=1;$roll=mysqli_fetch_assoc($solve);$i++){
                    if($i%2==0)continue;
            ?>      
                    <tr>
                        <td><?= $roll["bid"]?></td>
                        <td><?= $roll["goodsName"]?></td>
                        <td><?= $roll["amount"]?></td>  
                        <td><?= $roll["total"]?></td>
                    </tr>
                <?php }
                  if(isset($_POST["ongoing"])){
                ?>                
                <form method="post">
                <tr><td><td><td><td><input type="submit" name="deleteB" class="btn-danger" value="取消訂單<?=$bid?>"></td></tr>
                  <input type="hidden" name="bid" value="<?=$bid?>">
                </form>
        <?php }} ?>
    </tbody>
    <?php } ?>
  </table>
</div>  
        <?php if($_SESSION["power"]>0){?>
          <div id="cart"><a href="cart.php"><img src="image/cart.png" width="50px" height="50px"></a></div>
        <?php }?>
</body>
</html>
