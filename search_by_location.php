<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');
$locations = mysqli_query($db,"SELECT * FROM equip_location;");


?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>

  <body>
  <?php include('navi.php');?>
  
  <h1> 输入地点编号，并点击查询 </h1>

  <form action = "search_location_result.php" method = "post">


        地点编号 
        <dd> <input type = "number" name = "location_id" value = "" /></dd>
        </dl>


    <input type = "submit" name = "submit"  value = "查询地点"/>

    </form>

<h1> 地点列表 </h1>
如果不知道地点编号，请使用浏览器的「在页面中查找」功能在以下列表中找到目的地点的编号。<br>
如果查询地点不在以下地点列表中，请联系仓库管理员添加地点。<br>


  <table>

      <td> 位置编号 </td>
      <td> 位置名称 </td>

      <?php 

        foreach($locations as $location ) { ?>
      <tr>
        <?php foreach($location as $element) { echo "<td>".$element."</td>";}  ?>
        
      </tr>
      <?php } 
      mysqli_free_result($locations);
      ?>
    </table>


  </body>
</html>
