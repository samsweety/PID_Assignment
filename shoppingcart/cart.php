<?php
    session_start();
    if(!isset($_SESSION["power"])){
        header("location:index.php");
        exit();
    }else if($_SESSION["power"]<0){
        header("location:index.php");
        exit();
    }
    $uid=$_SESSION["uid"];
    if(isset($_POST["delete"])){
        unset($_SESSION[$_POST["id"]]);
    }
    if(isset($_POST["edit"])){
        if(!is_numeric($_POST["amount"])){
            echo "輸入的值並非數字";
        }else if($_POST["amount"]<0){
           echo "輸入的值小於零";
        }
        else{
            $gid=$_POST["id"];
            $_SESSION[$gid]=$_POST["amount"];
        }
    }
    
    
?>

<?php
    $link=mysqli_connect("localhost","root","root","shoppingMall");
    mysqli_query($link,"set names utf-8");
        $sql=<<<sql
            select * from goods;
            sql;
    $result=mysqli_query($link,$sql);

    if(isset($_POST["order"])){
        $sqlb=<<<sql
            insert into book (uid) value ($uid) ;
            sql;
        mysqli_query($link,$sqlb);
        $sqlbid=mysqli_query($link,"SELECT LAST_INSERT_ID()");
        $row=mysqli_fetch_assoc($sqlbid);
        $bid=$row["LAST_INSERT_ID()"];
        $sqlbdinsert="";
        for($i=1;$i<5;$i++){
            $id="id".$i;
            if(!isset($_SESSION[$id])){
                continue;
            }else{
                $amount=$_SESSION[$id];
                $sqlbdinsert.="($bid,$i,$amount),";
                unset($_SESSION[$id]);
            }
        }
        $sqlbdinsert=substr($sqlbdinsert,0,-1);
        $sqlbd=<<<sql
            insert into bookDetail (bid,gid,amount) values $sqlbdinsert;
            sql;
        mysqli_query($link,$sqlbd);
        header("location:index.php");
        exit();
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
  <h2>已訂購貨品</h2>          
  <table class="table table-dark table-hover" >
    <thead>
    <form method="post">
      <tr>
        <th>品名</th>
        <th>價格</th>
        <th>數量</th>
        <th></th>        
        <th ><input type="submit" name="order" id="order" class="btn-success" value="確認訂購"></th>        
      </tr>
      </form>
        <?php for($i=1;$row=mysqli_fetch_assoc($result);$i++){
            $id="id".$i;
            if(!isset($_SESSION[$id])){
                continue;
            }else{               
        ?>
        <form method="post">
        <tr>
            <td><?= $row["goodsName"]?></td>
            <td><?= $row["goodsPrice"]?></td>
            <td><?= $_SESSION[$id]?></td>
            <td><input type="text" name="amount" id="amount" style="width:50px" > </td>
            <td><input type="submit" name="edit" id="edit" class="btn-outline-success" value="修改數量">
                <input type="submit" name="delete" id="delete" class="btn-outline-danger" value="刪除此品項">
                <input type="hidden" name="id" value="<?=$id?>">
            </td>
        </tr>    
        </form>
        <?php }}?>
    </thead>
  </table>
</div>  
        <?php if($_SESSION["power"]>0){?>
          <div id="cart"><a href="cart.php"><img src="image/cart.png" width="50px" height="50px"></a></div>
        <?php }?>
</body>
</html>
