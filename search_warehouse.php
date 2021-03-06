<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$location_id = 0;

if(is_post_request()){

  $location_id = $_POST['location_id'] ?? '';
}

// Create a view for the specific warehouse storage
$query = "CREATE VIEW in_store_equip".$location_id." AS
        SELECT * FROM
        ( SELECT relocation_record_id, equip_id, to_location, MAX(relocation_date) AS date
        FROM relocation_record
        GROUP BY equip_id DESC) AS temp WHERE to_location  =".$location_id." ;";

mysqli_query($db,$query);

// Create a view without equipment-in-overhaul
$query = "CREATE VIEW available_equip".$location_id." AS

        SELECT * FROM 
            ( SELECT 
                in_store_equip".$location_id.".equip_id AS leftID, 
                to_location AS current_location 
            FROM in_store_equip".$location_id." 
            LEFT JOIN equip_in_overhaul         
            ON 
                in_store_equip".$location_id.".equip_id = equip_in_overhaul.equip_id 
            
            WHERE mro_type IS NULL ) AS temp 
        LEFT JOIN equip_info 
            ON 
                leftID = equip_id;";


mysqli_query($db,$query);

// Create a view for equipment-in-overhaul
$query = "CREATE VIEW unavailable_equip".$location_id." AS

        SELECT * FROM 
            ( SELECT 
                in_store_equip".$location_id.".equip_id AS leftID, 
                to_location AS current_location 
            FROM in_store_equip".$location_id." 
            LEFT JOIN equip_in_overhaul         
            ON 
                in_store_equip".$location_id.".equip_id = equip_in_overhaul.equip_id 
            
            WHERE mro_type IS NOT NULL ) AS temp 
        LEFT JOIN equip_info 
            ON 
                leftID = equip_id;";

mysqli_query($db,$query);

// Get available equipment 
$query = "SELECT
    available_equip".$location_id.".equip_id,
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

FROM  available_equip".$location_id." 
    LEFT JOIN equip_location ON available_equip".$location_id.".current_location = equip_location.location_id
    LEFT JOIN equip_type ON available_equip".$location_id.".type_id = equip_type.type_id
    LEFT JOIN equip_model ON available_equip".$location_id.".model_id = equip_model.model_id
    LEFT JOIN equip_brand ON available_equip".$location_id.".brand_id = equip_brand.brand_id
    LEFT JOIN equip_owner ON available_equip".$location_id.".owner_id = equip_owner.owner_id
    LEFT JOIN equip_origin ON available_equip".$location_id.".origin_id = equip_origin.origin_id
    LEFT JOIN equip_supplier ON available_equip".$location_id.".supplier_id = equip_supplier.supplier_id;
";

$available_equip = mysqli_query($db,$query);

// Get unavailable equipment 
$query = "SELECT
    unavailable_equip".$location_id.".equip_id,
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

FROM  unavailable_equip".$location_id." 
    LEFT JOIN equip_location ON unavailable_equip".$location_id.".current_location = equip_location.location_id
    LEFT JOIN equip_type ON unavailable_equip".$location_id.".type_id = equip_type.type_id
    LEFT JOIN equip_model ON unavailable_equip".$location_id.".model_id = equip_model.model_id
    LEFT JOIN equip_brand ON unavailable_equip".$location_id.".brand_id = equip_brand.brand_id
    LEFT JOIN equip_owner ON unavailable_equip".$location_id.".owner_id = equip_owner.owner_id
    LEFT JOIN equip_origin ON unavailable_equip".$location_id.".origin_id = equip_origin.origin_id
    LEFT JOIN equip_supplier ON unavailable_equip".$location_id.".supplier_id = equip_supplier.supplier_id;
";

$unavailable_equip = mysqli_query($db,$query);



?>

<!doctype html>

<html lang="en">
  <head>
    <title>????????????????????????</title>
    <meta charset="utf-8">

  </head>

  <body>

  <?php include('navi.php');?>

    <h1> ??????????????? </h1>

    <h1> ???????????? </h1>

      <form action = "search_warehouse.php" method = "post">


            ???????????? 
            <dd> <input type = "number" name = "location_id" value = "" /></dd>
            </dl>


        <input type = "submit" name = "submit"  value = "????????????"/>

      </form>


    <h2> ???????????? </h2>
    <table style = "width: 100%" >

      <td> ???????????? </td>
      <td> ???????????? </td>
      <td> ???????????? </td>
      <td> ?????? </td>
      <td> ?????? </td>
      <td> ????????? </td>
      <td> ?????? </td>
      <td> ????????? </td>
      <td> ??? </td>
      <td> ??? </td>
      <td> ??? </td>
      <td> ??? </td>
      <td> ???????????? </td>
      <td> ??????????????? </td>
      <td> ???????????? </td>


      <?php //line 136-149 show query result 

        foreach($available_equip as $equip ) { ?>
      <tr>
        <?php foreach($equip as $element) { 
          
          echo "<td>".$element."</td>";}  ?>
        
      </tr>
      <?php } 
      mysqli_free_result($available_equip);
      ?>
    </table>

    <h2> ??????????????? </h2>

    <table style = "width: 100%" >

      <td> ???????????? </td>
      <td> ???????????? </td>
      <td> ???????????? </td>
      <td> ?????? </td>
      <td> ?????? </td>
      <td> ????????? </td>
      <td> ?????? </td>
      <td> ????????? </td>
      <td> ??? </td>
      <td> ??? </td>
      <td> ??? </td>
      <td> ??? </td>
      <td> ???????????? </td>
      <td> ??????????????? </td>
      <td> ???????????? </td>


      <?php //line 136-149 show query result 

        foreach($unavailable_equip as $equip ) { ?>
      <tr>
        <?php foreach($equip as $element) { 
          
          echo "<td>".$element."</td>";}  ?>
        
      </tr>
      <?php } 
      mysqli_free_result($unavailable_equip);
      ?>
    </table>



</body>
</html>