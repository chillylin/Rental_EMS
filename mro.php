<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$all_equip = mysqli_query($db,"SELECT
                                equip_id,
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

                        FROM  equip_info 
                        LEFT JOIN equip_type ON equip_info.type_id = equip_type.type_id
                        LEFT JOIN equip_model ON equip_info.model_id = equip_model.model_id
                        LEFT JOIN equip_brand ON equip_info.brand_id = equip_brand.brand_id
                        LEFT JOIN equip_owner ON equip_info.owner_id = equip_owner.owner_id
                        LEFT JOIN equip_origin ON equip_info.origin_id = equip_origin.origin_id
                        LEFT JOIN equip_supplier ON equip_info.supplier_id = equip_supplier.supplier_id;");


?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>

  <body>

  <?php include('navi.php');?>

    <h1> 查询 MRO 记录 </h1>

      <form action = "mro_record_search.php" method = "post">
            设备编号 
            <dd> <input type = "number" name = "equip_id" value = "" /></dd>
            </dl>
        <input type = "submit" name = "submit"  value = "查询 MRO 记录"/>
      </form>

    <h1> 添加 MRO 记录 </h1>

    <form action = "mro_record_modify.php" method = "post">
        设备编号 
        <dd> <input type = "number" name = "equip_id" value = "" /></dd>
        </dl>
    <input type = "submit" name = "submit"  value = "修改 MRO 记录"/>
    </form>
    
    <h1> 设备清单 </h1>
    <table class="searchable sortable"  style = "width: 100%"  >
    <thead>
      <tr>
      <td> 查询 </td>
      <td> 添加 </td>
      <td> 设备编号 </td>
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
      </tr>
    </thead>
    <tbody>
      <?php

        foreach($all_equip as $equip ) { 
            echo "<tr>";
            echo "<td><a href = mro_record_search.php?equip_id=".$equip["equip_id"].">查询</a></td>";
            echo "<td><a href = mro_record_modify.php?equip_id=".$equip["equip_id"].">添加</a></td>";
            foreach($equip as $element) { echo "<td>".$element."</td>";} 
            echo "</tr>";        
        } 
      mysqli_free_result($all_equip);
      ?>
    </tbody>
    </table>
  </body>
</html>
