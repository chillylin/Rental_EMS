<?php
require_once('./private/initialize.php');
require_once('./private/auth_check.php');

$status = "";
$success = 0;

$filename = '';

if(is_post_request()){

    $target_dir = "uploads/";
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

    // Allow certain file formats
    if($imageFileType != "xlsx" ) {
        $status =   "仅支持 XLSX 文档。请使用 Excel 另存为正确的扩展名。";
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
    } else {
        $status =   "文档未能上传，请联系管理员。";
    }
    }
}
?>

<!DOCTYPE html>
<html>
<body>


<?php include('navi.php');?>

<h1>租赁设备管理系统</h1>

<h2> 数据导入 </h2>

在本页面上导入已经上传的 schedule 文档。

<?php if ($success == 0) { echo $status; echo "<br><br> 如果文档尚未上传，请在下方上传文档:"; ?>
<form action="upload.php" method="post" enctype="multipart/form-data">
  在此处选择需要上传的 schedule 文档:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="选择文档后，点此上传 Schedule 文档" name="submit">
</form>
<?php }
else {echo $status; ?>

    

    <?php } ?>
<br>
如果文档已经上传，请在此处输入文件名后点"读取文档"。
<div>
    <form action = "load.php" method = "post">
    
    <dl>
        <dt> 文档名 </dt>
        <dd> <input type = "text" name = "filename" value = "<?php echo $filename ?>" /></dd>
    </dl>

    <input type = "submit" name = "submit"  value = "读取文档"/>

    </form>
</div>
</body>
</html>