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
    <title>????????????????????????</title>
    <meta charset="utf-8">

  </head>
  <?php include('navi.php');?>

  <body>
  <h1>????????????????????????</h1>

    ??????????????????????????????
  
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

    ??????????????????????????????

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

    
    
            <h3> ???????????? </h3>
    <table class="searchable sortable"  style = "width: 100%"  >
    <thead>
      <tr>
      <th> ???????????? </th>
      <th> ???????????? </th>
      <th> ???????????? </th>
      <th> ?????? </th>
      <th> ?????? </th>
      <th> ????????? </th>
      <th> ?????? </th>
      <th> ????????? </th>
      <th> ??? </th>
      <th> ??? </th>
      <th> ??? </th>
      <th> ??? </th>
      <th> ???????????? </th>
      <th> ??????????????? </th>
      <th> ???????????? </th>
      </tr>
    </thead>
    <tbody>
    <tr><td>????????????</td></tr>
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
    <tr><td>???????????????</td></tr>
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


    <tr><td> ??????????????? </td></tr>
    
    
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

    
