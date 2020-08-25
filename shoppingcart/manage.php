<?php
    session_start();
    if(!isset($_SESSION["power"])){
        header("location:index.php");
        exit();
    }else if($_SESSION["power"]>0){
        header("location:index.php");
        exit();
    }
?>

<?php
  $link=mysqli_connect("localhost","root","root","shoppingMall");
  mysqli_query($link,"set names utf-8");
  if(isset($_POST["addGood"])){
        $gn=$_POST["goodsName"];
        $gp=$_POST["goodsPrice"];
        $sql=<<<sql
            insert into goods (goodsName,goodsPrice) values ("$gn",$gp);
            sql;
        mysqli_query($link,$sql);
        
  }
  if(isset($_POST["deleteGood"])){      
        $gid=$_POST["gid"];
        $sql=<<<sql
            delete from goods where gid=$gid;
            sql;
        mysqli_query($link,$sql);
  }
  if(isset($_POST["editPrice"])){
    $price=$_POST["fixPrice"];
    $gid=$_POST["gid"];
    $sql=<<<sql
        update  goods set goodsPrice=$price where gid=$gid;
        sql;
    mysqli_query($link,$sql);
  }


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>後台管理</title>
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
    <?php if($_SESSION["power"]<0){?>
        <a class="nav-link" style="color:yellow" href="manage.php">管理</a>
    <?php } ?>
    <?php if($_SESSION["power"]>0){?>
        <a class="nav-link" style="color:yellow" href="member.php">會員中心</a>
    <?php } ?>
  </ul>
</nav>

<div class="container">
<table>
        <tr></tr>
        <th><h2>管理頁面</h2></th>
        <form method="post">
        <th><input type="submit" name="glist" id="glist" class="btn-outline-primary" value="商品列表"></th>
        <th><input type="submit" name="ulist" id="ulist" class="btn-outline-primary" value="顧客列表"></th>
        <th><input type="submit" name="blist" id="blist" class="btn-outline-primary" value="訂單列表"></th>
        </form>
</table>
  <table class="table table-dark table-hover">
    <?php if(isset($_POST["glist"])){
        $sql=<<<sql
            select * from goods;
            sql;
        $result=mysqli_query($link,$sql);
        ?><tr>
                <td>商品編號</td>
                <td>商品名稱</td>
                <td>價格</td>
                <td>修改價格</td>
                <td> </td>
            </tr>
            <form method="post">
            <tr>
                <td>自動產生</td>
                <td><input type="text" name="goodsName" id="goodsName"></td>
                <td><input type="text" name="goodsPrice" id="goodsPrice"></td>
                <td></td>
                <td><input type="submit" name="addGood" id="addGood" class="btn-success" value="新增">
                    <input type="hidden" name="glist" value="1">
                </td>
            </tr>
            </form>
        <?php
        for(;$row=mysqli_fetch_assoc($result);){ 
                $gid=$row["gid"];
            ?>
            <form method="post">
            <tr>
                <td><?= $gid?><input type="hidden" name="gid" value="<?=$gid?>"></td>
                <td><?= $row["goodsName"]?></td>
                <td><?= $row["goodsPrice"]?></td>
                <td><input type="text" name="fixPrice" id="fixPrice"></td>
                <td><input type="submit" name="editPrice" id="editPrice" class="btn-success" value="修改">
                    <input type="submit" name="deleteGood" id="deleteGood" class="btn-danger" value="刪除">
                    <input type="hidden" name="glist" value="1">
                </td>
            </tr>
            </form>
        <?php }
    }?>
    
    
  </table>
</div>  
        
</body>
</html>
