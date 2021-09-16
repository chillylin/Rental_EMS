<?php

    require_once('./private/initialize.php');
    require_once('./private/user.php');


    if(is_post_request()){
    
        $username = strtolower(trim($_POST['username']));
        $password = $_POST['password'];
        $hash_pass = md5($password.$username);
        
        $query = 'SELECT * FROM users WHERE username ="'.$username.'" AND userpass = "'.$hash_pass.'"';

        $result =  mysqli_query($db,$query)->fetch_row();
        
        echo $query;
        print_r($result);


        if(!$result){
            echo 'incorrect password';
        }else{
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $result[0];
            
            header('Location: index.php');
        }
    
    }

?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>

  <body>

    <h1>租赁设备管理系统</h1>

    <h2> 用户登录 </h2>

    <form action = "login.php" method = "post">


            <dd> 用户名 </dd>
            <dd> <input type = "text" name = "username" value = "" /></dd>
            <dd> 密码 </dd>
            <dd> <input type = "text" name = "password" value = "" /></dd>


        <input type = "submit" name = "submit"  value = "登录"/>

      </form>


  </body>
</html>