<?php

require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$mro_types = mysqli_query($db,"SELECT * FROM mro_type;");
$equip_id = 0;
$status = '';
$success = 0;
$filename = '';


if (array_key_exists ( 'equip_id' , $_GET ) )
{

    $equip_id = $_GET['equip_id'] ?? '';
    // Build a folder if not exist
    if (! is_dir("./mro/".$equip_id))
    { mkdir("./mro/".$equip_id,0744); }
}


if(is_post_request()){

    $equip_id = $_POST['equip_id'] ?? '';
    // Build a folder if not exist
    if (! is_dir("./mro/".$equip_id))
    { mkdir("./mro/".$equip_id); }

    if (array_key_exists ( 'mro_type' , $_POST ) )
    {

        $target_dir = "mro/".$equip_id."/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


        // Check if file already exists
        if (file_exists($target_file)) {
            $status =  "此文件已经存在，请修改文件名后重试";
        $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            $status =   "文件过大，请联系管理员";
        $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            //$status =   "文档无法上传，请重试。如重试后仍然不成功请联系管理员。";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $status =   "文档 ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " 已经成功上传.";
                $success = 1;
                $filename = basename( $_FILES["fileToUpload"]["name"]);
            } 
            else {$status = "文档未能上传，请联系管理员。";}
        }



        

    
        $insert_query = "INSERT INTO mro_record_online 
            (equip_id, mro_type, mro_date, mro_file_name) 
            VALUES 
            (".$_POST['equip_id'].",".$_POST['mro_type'].",'".$_POST['mro_date']."','".$filename."');";

        mysqli_query($db,$insert_query);
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
  <?php include('navi.php');?>

    <h1> 修改 MRO 记录 </h1>
    
    <?php if ($success == 0) { echo $status; echo "<br><br> 如果文档尚未上传，请在下方上传文档:"; ?>
    <form action="mro_record_modify.php" method="post" enctype="multipart/form-data">
    <dt> 填写 MRO 记录对应的设备序号 </dt>
    <dd> <input type = "text" name = "equip_id" value = "<?php echo $equip_id; ?>" /></dd>

    <dt> MRO 执行日期 </dt>
        <dd> <input type = "date" name = "mro_date" value = "" /></dd>
    </dl>
    <dl>
        <dt> MRO 类别 </dt>
        <dd> 
            
            <select id="mro_type" name="mro_type">
            <?php
                foreach ($mro_types as $mro_type)
                {
                    echo '<option value="'.$mro_type['mro_type_id'].'">'.$mro_type['mro_type_name'].'</option>';
                }
            
            ?>
            </select>
    
        </dd>
    </dl>

    在此处选择需要上传的 附件 文档:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="选择附件文档后，点此上传附件" name="submit">
    </form>

    <?php }
    else {echo $status;
    echo "<a href='mro_record_modify.php'> 添加新记录 </a>";} ?>
