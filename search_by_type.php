<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$equip_types = mysqli_query($db,"SELECT * FROM  equip_type;");
$type_id = '';
$model_id = '';
$models = '';
$available_equips = '';
$unavailable_equips = '';
$at_site_equips  = '';


if (array_key_exists ( 'type_id' , $_GET ) )
{
    $type_id = $_GET['type_id'];

    $query = "SELECT model_id, model_name 
                FROM (SELECT model_id AS LEFTID FROM equip_info WHERE type_id = ".$type_id." GROUP BY model_id) AS temp
                LEFT JOIN equip_model ON LEFTID = equip_model.model_id;";
    $models = mysqli_query($db,$query);

    if (array_key_exists ( 'model_id' , $_GET ) )
    {
        $model_id = $_GET['model_id'];
        

        // Retrieving available equipments
        $query = "SELECT
                temptable.equip_id,
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
            
            FROM (SELECT * FROM temptable WHERE type_id = ".$type_id." AND model_id = ".$model_id.") AS temptable 
                LEFT JOIN equip_location ON temptable.current_location = equip_location.location_id
                LEFT JOIN equip_type ON temptable.type_id = equip_type.type_id
                LEFT JOIN equip_model ON temptable.model_id = equip_model.model_id
                LEFT JOIN equip_brand ON temptable.brand_id = equip_brand.brand_id
                LEFT JOIN equip_owner ON temptable.owner_id = equip_owner.owner_id
                LEFT JOIN equip_origin ON temptable.origin_id = equip_origin.origin_id
                LEFT JOIN equip_supplier ON temptable.supplier_id = equip_supplier.supplier_id;";
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        mysqli_query($db,"CREATE TABLE temptable AS SELECT * FROM available_equip;");
        $available_equips = mysqli_query($db,$query);
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");


        // Retrieve unavailable equipments which is in warehouse and under repair
        $query = "SELECT
                temptable.equip_id,
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
            
            FROM (SELECT * FROM temptable WHERE type_id = ".$type_id." AND model_id = ".$model_id.") AS temptable 
                LEFT JOIN equip_location ON temptable.current_location = equip_location.location_id
                LEFT JOIN equip_type ON temptable.type_id = equip_type.type_id
                LEFT JOIN equip_model ON temptable.model_id = equip_model.model_id
                LEFT JOIN equip_brand ON temptable.brand_id = equip_brand.brand_id
                LEFT JOIN equip_owner ON temptable.owner_id = equip_owner.owner_id
                LEFT JOIN equip_origin ON temptable.origin_id = equip_origin.origin_id
                LEFT JOIN equip_supplier ON temptable.supplier_id = equip_supplier.supplier_id;";
        

        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        mysqli_query($db,"CREATE TABLE temptable AS SELECT * FROM unavailable_equip;");
        $unavailable_equips = mysqli_query($db,$query);
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        
        // Retrieve unavailable equipments which is sent to the clients
        $query = "SELECT
                    temptable.equip_id,
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
                
                FROM (SELECT temptable.equip_id,
                        to_location AS current_location,	
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
                    FROM temptable 
                    LEFT JOIN equip_info 
                    ON temptable.equip_id = equip_info.equip_id 
                    WHERE type_id = ".$type_id." AND model_id = ".$model_id.") AS temptable 
                LEFT JOIN equip_location ON temptable.current_location = equip_location.location_id
                LEFT JOIN equip_type ON temptable.type_id = equip_type.type_id
                LEFT JOIN equip_model ON temptable.model_id = equip_model.model_id
                LEFT JOIN equip_brand ON temptable.brand_id = equip_brand.brand_id
                LEFT JOIN equip_owner ON temptable.owner_id = equip_owner.owner_id
                LEFT JOIN equip_origin ON temptable.origin_id = equip_origin.origin_id
                LEFT JOIN equip_supplier ON temptable.supplier_id = equip_supplier.supplier_id;";
        

        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        mysqli_query($db,"CREATE TABLE temptable AS SELECT * FROM at_site_equip;");
        $at_site_equips = mysqli_query($db,$query);
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        
    }
    else{
        // Retrieving available equipments
        $query = "SELECT
                temptable.equip_id,
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
            
            FROM (SELECT * FROM temptable WHERE type_id = ".$type_id.") AS temptable 
                LEFT JOIN equip_location ON temptable.current_location = equip_location.location_id
                LEFT JOIN equip_type ON temptable.type_id = equip_type.type_id
                LEFT JOIN equip_model ON temptable.model_id = equip_model.model_id
                LEFT JOIN equip_brand ON temptable.brand_id = equip_brand.brand_id
                LEFT JOIN equip_owner ON temptable.owner_id = equip_owner.owner_id
                LEFT JOIN equip_origin ON temptable.origin_id = equip_origin.origin_id
                LEFT JOIN equip_supplier ON temptable.supplier_id = equip_supplier.supplier_id;";
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        mysqli_query($db,"CREATE TABLE temptable AS SELECT * FROM available_equip;");
        $available_equips = mysqli_query($db,$query);
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");


        // Retrieve unavailable equipments which is in warehouse and under repair
        $query = "SELECT
                temptable.equip_id,
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
            
            FROM (SELECT * FROM temptable WHERE type_id = ".$type_id.") AS temptable 
                LEFT JOIN equip_location ON temptable.current_location = equip_location.location_id
                LEFT JOIN equip_type ON temptable.type_id = equip_type.type_id
                LEFT JOIN equip_model ON temptable.model_id = equip_model.model_id
                LEFT JOIN equip_brand ON temptable.brand_id = equip_brand.brand_id
                LEFT JOIN equip_owner ON temptable.owner_id = equip_owner.owner_id
                LEFT JOIN equip_origin ON temptable.origin_id = equip_origin.origin_id
                LEFT JOIN equip_supplier ON temptable.supplier_id = equip_supplier.supplier_id;";
        

        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        mysqli_query($db,"CREATE TABLE temptable AS SELECT * FROM unavailable_equip;");
        $unavailable_equips = mysqli_query($db,$query);
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        
        // Retrieve unavailable equipments which is sent to the clients
        $query = "SELECT
                    temptable.equip_id,
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
                
                FROM (SELECT temptable.equip_id,
                        to_location AS current_location,	
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
                    FROM temptable 
                    LEFT JOIN equip_info 
                    ON temptable.equip_id = equip_info.equip_id 
                    WHERE type_id = ".$type_id.") AS temptable 
                LEFT JOIN equip_location ON temptable.current_location = equip_location.location_id
                LEFT JOIN equip_type ON temptable.type_id = equip_type.type_id
                LEFT JOIN equip_model ON temptable.model_id = equip_model.model_id
                LEFT JOIN equip_brand ON temptable.brand_id = equip_brand.brand_id
                LEFT JOIN equip_owner ON temptable.owner_id = equip_owner.owner_id
                LEFT JOIN equip_origin ON temptable.origin_id = equip_origin.origin_id
                LEFT JOIN equip_supplier ON temptable.supplier_id = equip_supplier.supplier_id;";
        

        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");
        mysqli_query($db,"CREATE TABLE temptable AS SELECT * FROM at_site_equip;");
        $at_site_equips = mysqli_query($db,$query);
        mysqli_query($db,"DROP TABLE IF EXISTS temptable;");

    }
}


?>

<!doctype html>

<html lang="en">
  <head>
    <title>租赁设备管理系统</title>
    <meta charset="utf-8">

  </head>
  <?php include('navi.php');?>

  <body>
  <h1>租赁设备管理系统</h1>

    第一步，选定设备类别
  
    <table style = "width: 100%" > 

        <?php
            
                echo "<tr>";

                foreach ($equip_types as $equip_type)
                {

                    echo "<td>";
                    echo "<a href = 'search_by_type.php?type_id=".$equip_type['type_id']."'>";
                    echo $equip_type['type_name'];
                    echo "</a>";
                    echo "</td>";
                    if ($equip_type['type_id'] % 4 == 0){
                        echo "</tr><tr>";
                    }

                }
                echo "</tr>";
            
            
        
        ?>
    </table>

    第二步，选定设备型号

    <table style = "width: 100%" > 

        <?php

            if ($models != '')
            {
                echo "<tr>";

                foreach ($models as $model)
                {

                    echo "<td>";
                    echo "<a href = 'search_by_type.php?type_id=".$type_id."&model_id=".$model['model_id']."'>";
                    echo $model['model_name'];
                    echo "</a>";
                    echo "</td>";
                    if ($model['model_id'] % 4 == 0){
                        echo "</tr><tr>";
                    }

                }
                echo "</tr>";
            }
        ?>
    </table>

    
    
            <h3> 查询结果 </h3>
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
    <tr><td>可用设备</td></tr>
      <?php
        if ($available_equips != '')
        {

            foreach($available_equips as $equip ) { 
                echo "<tr>";
                foreach($equip as $element) { echo "<td>".$element."</td>";}
                echo "</tr>"; 
             } 
            mysqli_free_result($available_equips);
        }
      ?>
    <tr><td>修理中设备</td></tr>
      <?php
        if ($unavailable_equips != '')
        {

            foreach($unavailable_equips as $equip ) { 
                echo "<tr>";
                foreach($equip as $element) { echo "<td>".$element."</td>";}
                echo "</tr>"; 
             } 
            mysqli_free_result($unavailable_equips);
        }
      ?>


    <tr><td> 在现场设备 </td></tr>
    
    
      <?php
        if ($at_site_equips != '')
        {

            foreach($at_site_equips as $equip ) { 
                echo "<tr>";
                foreach($equip as $element) { echo "<td>".$element."</td>";}
                echo "</tr>"; 
             } 
            mysqli_free_result($at_site_equips);
        }
      ?>
    </tbody>
    </table>

    
