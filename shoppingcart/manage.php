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
  if(isset($_POST["addGood"])){   //商品列表function 新增
        $gn=$_POST["goodsName"];
        $gp=$_POST["goodsPrice"];
        $sql=<<<sql
            insert into goods (goodsName,goodsPrice) values ("$gn",$gp);
            sql;
        mysqli_query($link,$sql);
        
  }
  if(isset($_POST["deleteGood"])){      //商品列表function 刪除
        $gid=$_POST["gid"];
        $sql=<<<sql
            delete from goods where gid=$gid;
            sql;
        mysqli_query($link,$sql);
  }
  if(isset($_POST["editPrice"])){  //商品列表function 修改價格
    $price=$_POST["fixPrice"];
    $gid=$_POST["gid"];
    $sql=<<<sql
        update  goods set goodsPrice=$price where gid=$gid;
        sql;
    mysqli_query($link,$sql);
  }
  if(isset($_POST["addManager"])){ //使用者列表function 增加管理員
    $userName=$_POST["userName"];
    $pw=$_POST["userPw"];
    $sql=<<<sql
      insert into users (userName,pw,power) values ("$userName","$pw",-1);
      sql;
    mysqli_query($link,$sql);
  }
  if(isset($_POST["deleteUser"])){    //使用者列表function 移除使用者
    $uid=$_POST["uid"];
    $sql=<<<sql
      delete from users where uid=$uid;
      sql;
    mysqli_query($link,$sql);
  }
  if(isset($_POST["userBan"])){     //使用者列表function 封鎖使用者
    $uid=$_POST["uid"];
    $sql=<<<sql
      update users set power=2 where uid=$uid;
      sql;
    mysqli_query($link,$sql);
  }
  if(isset($_POST["userUnban"])){   //使用者列表function 解封使用者
    $uid=$_POST["uid"];
    $sql=<<<sql
      update users set power=1 where uid=$uid;
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
        <th><input type="submit" name="ulist" id="ulist" class="btn-outline-primary" value="使用者列表"></th>
        <th><input type="submit" name="blist" id="blist" class="btn-outline-primary" value="訂單列表"></th>
        </form>
</table>
  <table class="table table-dark table-hover">
      
    <?php if(isset($_POST["glist"])){ //商品列表start----------------
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
    } //商品列表end------------- ?> 

    <?php if(isset($_POST["ulist"])){ //使用者列表start----------------
        $sql=<<<sql
            select * from users;
            sql;
        $result=mysqli_query($link,$sql);
        ?>
          <tr>
              <td>使用者編號</td>
              <td>使用者名稱</td>
              <td>權限</td>
              <td></td>
              <td></td>
          </tr>
          <form method="post"><input type="hidden" name="ulist" value="1">
            <tr>
                <td>自動產生</td>
                <td><input type="text" name="userName" id="userName" placeholder="帳號"></td>
                <td><input type="text" name="userPw" id="userPw" placeholder="密碼"></td>
                <td></td>
                <td><input type="submit" name="addManager" id="addManager" class="btn-success" value="新增管理員">
                </td>
            </tr>
            </form>            
        <?php
          for(;$row=mysqli_fetch_assoc($result);){
            ?>
          <form method="post"><input type="hidden" name="ulist" value="1">
            <tr>
              <td><?= $row["uid"]?><input type="hidden" name="uid" id="uid" value="<?=$row["uid"]?>"></td>
              <td><?= $row["userName"]?></td>
              <?php switch($row["power"]){
                case -1: ?>
                <td>管理員</td>
                <?php break; 
                case 1: ?>
                <td>顧客</td>
                <?php break;
                case 2: ?>
                <td>已封鎖</td>
                <?php break;}?>

              <?php switch($row["power"]){ 
                case -1: ?>
                <td></td>
                <?php break;                
                case 1: ?>
                <td><input type="submit" name="userBan" id="userBan" class="btn-outline-danger" value="封鎖"></td>
                <?php break;
                case 2: ?>
                <td><input type="submit" name="userUnban" id="userUnban" class="btn-danger" value="解除封鎖" ></td>
              <?php break;}?>

              <td><input type="submit" name="deleteUser" id="deleteUser" class="btn-warning" value="移除"></td>
            </tr>
          </form>
        <?php
          } 
    } //使用者列表end-------------- ?>   

  <?php if(isset($_POST["blist"])){ // 訂單列表start----------------
        $sql=<<<sql
            select b.bid,u.userName,sum(bd.amount*g.goodsPrice) as total ,b.cond from book as b
            inner join users as u on b.uid=u.uid
            inner join bookDetail as bd on b.bid=bd.bid
            inner join goods as g on bd.gid=g.gid
            group by bid;
          sql;
        $result=mysqli_query($link,$sql);
        ?><tr>
                <td>訂單編號</td>
                <td>下訂者</td>
                <td>總金額</td>
                <td>出貨狀況</td>
                <td>明細</td>
            </tr>            
        <?php
        for(;$row=mysqli_fetch_assoc($result);){?>
            <tr>
                <td><?=$row["bid"]?></td>
                <td><?=$row["userName"]?></td>
                <td><?=$row["total"]?></td>
                <td><?= ($row["cond"]==0)?"未出貨":"已出貨"?></td>
                <form method="post"> <input type="hidden" name="bid" value="<?= $row["bid"]?>">
                    <td><input type="submit" name="bd" id="bd" value="明細"></td>
                </form>
            </tr>        
          <?php 
        }
         
      } //訂單列表end------------- 
    if(isset($_POST["bd"])){
      $bid=$_POST["bid"];
      $sql=<<<sql
            select b.bid,u.userName,g.goodsName,bd.amount,sum(bd.amount*g.goodsPrice) as total ,b.cond from book as b
            inner join users as u on b.uid=u.uid
            inner join bookDetail as bd on b.bid=bd.bid
            inner join goods as g on bd.gid=g.gid
            where b.bid=$bid group by g.goodsName,bd.amount;
        sql;
      $result=mysqli_query($link,$sql);
      ?>
        <tr>
            <td>訂單編號</td>
            <td>下訂者</td>
            <td>商品名稱</td>
            <td>數量</td>
            <td>金額</td><form method="post">
            <td><input type="submit" name="blist" id="blist" class="btn-success" value="返回"></td></form>
        </tr>
      <?php
      for(;$row=mysqli_fetch_assoc($result);){?> 
        <tr>
            <td><?=$row["bid"]?></td>
            <td><?=$row["userName"]?></td>
            <td><?=$row["goodsName"]?></td>
            <td><?=$row["amount"]?></td>
            <td><?=$row["total"]?></td>
            <td></td>
        </tr>
        <?php
      }      
    }    
    ?> 

    
  </table>
</div>  
        
</body>
</html>
