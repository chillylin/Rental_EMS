<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$location_id = 0;

if(is_post_request()){

  $location_id = $_POST['location_id'] ?? '';
}


$query = '
SELECT 
        equip_id,
	      location_name,
        type_name,
        model_name,
        brand_name,
        owner_name,
        origin_name,
        supplier_name,
        scale_x,scale_y,scale_z,
        equip_weight,
        circuit_breaker,
        container,
        framework  
	FROM
	(SELECT 
        equip_by_location.equip_id,
        location_id,	
        type_id,	
        model_id,	
        brand_id,	
        origin_id,	
        owner_id,	
        supplier_id,	
        scale_x,	
        scale_y,
        scale_z,	
        equip_weight,	
        circuit_breaker,	
        situation,	
        container,	
        framework 
    FROM equip_by_location 
        LEFT JOIN equip_info 
        ON equip_by_location.equip_id = equip_info.equip_id 
        WHERE location_id = 
        '.$location_id.'
        ) AS temp 
    LEFT JOIN equip_location ON temp.location_id = equip_location.location_id
            LEFT JOIN equip_type ON temp.type_id = equip_type.type_id
            LEFT JOIN equip_model ON temp.model_id = equip_model.model_id
            LEFT JOIN equip_brand ON temp.brand_id = equip_brand.brand_id
            LEFT JOIN equip_owner ON temp.owner_id = equip_owner.owner_id
            LEFT JOIN equip_origin ON temp.origin_id = equip_origin.origin_id
            LEFT JOIN equip_supplier ON temp.supplier_id = equip_supplier.supplier_id;';


$equips = mysqli_query($db,$query);


?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>

  <body>

  <?php include('navi.php');?>

    <h1> 查询结果 </h1>

    <table>

      <td> 设备编号 </td>
      <td> 现在位置 </td>
      <td> 设备类型 </td>
      <td> 规格 </td>
      <td> 品牌 </td>
      <td> 所有人 </td>
      <td> 产地 </td>
      <td> 供应商 </td>
      <td> 长 </td>
      <td> 宽 </td>
      <td> 高 </td>
      <td> 重 </td>
      <td> 有无空开 </td>
      <td> 有无集装箱 </td>
      <td> 有无框架 </td>


      <?php //line 136-149 show query result 

        foreach($equips as $equip ) { ?>
      <tr>
        <?php foreach($equip as $element) { 
          
          echo "<td>".$element."</td>";}  ?>
        
      </tr>
      <?php } 
      mysqli_free_result($equips);
      ?>
    </table>

    <h1> 查询其他地点 </h1>

      <form action = "search_location_result.php" method = "post">


            地点编号 
            <dd> <input type = "number" name = "location_id" value = "" /></dd>
            </dl>


        <input type = "submit" name = "submit"  value = "查询地点"/>

      </form>

</body>
</html>