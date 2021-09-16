<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//echo `whoami`; 

echo 'error reporting';
require_once('./private/initialize.php');
require_once('./private/auth_check.php');
require_once('./private/user.php');


?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>

  <body>

  <?php include('navi.php') ?>

    <h1>租赁设备管理系统</h1>

    <h2> 数据管理 </h2>
    
    <a href = 'upload.php'> 上传数据 </a>

    <h2> 设备查询 </h2>
    <table>
    <td> <a href = 'current_available_all.php'> 显示所有可用设备 </a> </td> 
    <td> <a href = 'search_by_location.php'> 按地点查询 </a> </td> 
    <td> <a href = 'search_warehouse.php'> 在库设备查询 </a> </td> 
    <td> <a href = 'search_by_type.php'> 按类别查询 </a>     </td> 
    <td> <a href = 'mro.php'> MRO 记录 </a>   </td>
    </table>
    
    

  </body>
</html>
