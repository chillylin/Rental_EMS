<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$available_equip = mysqli_query($db,"SELECT * FROM  show_available_equip ORDER BY equip_id ASC;");


?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>

  <body>
      
  <?php include('navi.php');?>

    <h1> 所有现有可用设备 </h1>
    <table class="searchable sortable"  style = "width: 100%"  >
    <thead>
      <tr>
      <th> 设备编号 </th>
      <th> 现在位置 </th>
      <th> 设备类型 </th>
      <th> 规格 </th>
      <th> 品牌 </th>
      <th> 所有人 </th>
      <th> 产地 </th>
      <th> 供应商 </th>
      <th> 长 </th>
      <th> 宽 </th>
      <th> 高 </th>
      <th> 重 </th>
      <th> 有无空开 </th>
      <th> 有无集装箱 </th>
      <th> 有无框架 </th>
      </tr>
    </thead>
    <tbody>
      <?php

        foreach($available_equip as $equip ) { ?>
      <tr>
        <?php foreach($equip as $element) { echo "<td>".$element."</td>";}  ?>
        
      </tr>
      <?php } 
      mysqli_free_result($available_equip);
      ?>
    </tbody>
    </table>


  </body>
</html>
