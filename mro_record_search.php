<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$equip_id = 0;

if(is_post_request()){

    $equip_id = $_POST['equip_id'] ?? '';

    
}


if (array_key_exists ( 'equip_id' , $_GET ) )
{

    $equip_id = $_GET['equip_id'] ?? '';

    if (array_key_exists ( 'delete_id' ,$_GET  ) )
    {
      $filenames = mysqli_query($db,"SELECT mro_file_name FROM mro_record_online WHERE mro_record_id =".$_GET['delete_id']);
      
      foreach($filenames as $filename){
        unlink('mro/'.$equip_id.'/'.$filename['mro_file_name']);
      }
            
      mysqli_query($db,"DELETE FROM mro_record_online WHERE mro_record_id =".$_GET['delete_id']);
      
    }
}





$mro_records = mysqli_query($db,"SELECT * FROM mro_record_online WHERE equip_id = ".$equip_id.";");

?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>

  <body>
  <?php include('navi.php');?>

    <h1> 查询 MRO 记录结果 </h1>
    <table style = "widtd: 100%">

      <tr>
      <td> 删除记录 </td>
      <td> MRO 记录号 </td>
      <td> 设备编号 </td>
      <td> MRO 类型 </td>
      <td> 日期 </td>
      <td> 记录原始文档 </td>
      <td> 下载原始文档 </td>
      </tr>
   
 
      <?php

        foreach($mro_records as $record ) {
            echo "<tr>";
            echo "<td><a href=mro_record_search.php?equip_id=".$equip_id."&delete_id=".$record['mro_record_id'].">删除记录</a></td>";

            foreach($record as $element) { echo "<td>".$element."</td>";}
            echo "<td><a href=/mro/".$equip_id."/".$record['mro_file_name'].">下载</a></td>";

            echo "</tr>";    
        } 
      mysqli_free_result($mro_records);
      ?>

    </table>