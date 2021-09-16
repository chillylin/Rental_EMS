<?php 
echo 'error reporting';
require_once('./private/initialize.php');
require_once('./private/auth_check.php');
require_once('SimpleXLSX.php');

$code_tables = array("change_type.csv", 
                    "bar", "hello", "world");

$data_tables = array("change_record.csv",
                    );

$status = '';

function insert_codes($db , $tablename, $tablecol1, $tablecol2){


    $cleanquery = 'DELETE FROM '.$tablename;
    if (mysqli_query($db,$cleanquery)){
        //echo $tablename.' is cleaned';
    }
    else{
        echo $cleanquery."<br>";
        echo $tablename."' cleaning failed<br><br>";
    };

    
    $query = "INSERT INTO ".$tablename." (". $tablecol1.",".$tablecol2.") VALUES ";
    $code_table = read_csv('./csv/'. $tablename.'.csv');
    foreach( $code_table as $row){
        $query .= "('".$row[0]."','".$row[1]."'), ";
    }

    $query = substr( $query, 0, -2).';';

    if (mysqli_query($db,$query)){
        //echo $tablename.' data is successfully inserted';
    }
    else{
        echo $query."<br>";
        echo $tablename."data is not inserted<br><br>";
    };

}

function insert_records($db , $tablename){

    
    
    $cleanquery = 'DELETE FROM '.$tablename;
    if (mysqli_query($db,$cleanquery)){
        //echo $tablename.' is cleaned';
    }
    else{
        echo $cleanquery."<br>";
        echo $tablename."' cleaning failed<br><br>";
    };

    $code_table = read_csv('./csv/'. $tablename.'.csv');

    $firstflag = 1;
    foreach( $code_table as $row){

        if (1 == $firstflag){
            $firstflag = 0;
            $query = "INSERT INTO ".$tablename." (";
            foreach($row as $cell){
                $query .= $cell.", ";
            }
            $query = substr( $query, 0, -2);
            $query .= ") VALUES ";
        }
        else {
            $query .= "(";
            foreach($row as $cell){
                

                $query .= "'".$cell."', ";
            }
            $query = substr( $query, 0, -2);
            $query .= "), ";
        }
        
    }
    $query = substr( $query, 0, -2).';';
   
    if (mysqli_query($db,$query)){
        //echo $tablename.' data is successfully inserted';
    }
    else{
        echo $query."<br>";
        echo $tablename."data is not inserted<br><br>";
    };
}


function read_csv($file){

	$return_array = array();

	
	if (($handle = fopen($file, "r")) !== FALSE) {

        while (($data = fgetcsv($handle, 300, ",")) !== FALSE)
        {
			array_push($return_array, $data);
		}
		fclose($handle);
	}

	return $return_array;
}


if(is_post_request()){
	echo 'is post';
    $fname = $_POST['filename'] ?? '';

    if ($fname == ''){
        $status = '未输入文件名，请重试';
    }
    else{
        /* split excel file into csvs */
        // $command = escapeshellcmd('python3 xlsx2csv.py -a ./uploads/'.$fname.' ./csv/');
        // $output = shell_exec($command);

        /* MAIN Process start here */
        /* Temporarily stop foreign key check to enable inserting data */
        mysqli_query($db,'SET FOREIGN_KEY_CHECKS = 0;');
        
        // Import all definition of codes to the database

        $code_tables = read_csv('./csv/codedefinition.csv');

        foreach($code_tables as $code_table){

            insert_codes($db ,$code_table[0], $code_table[1],  $code_table[2]);
        }

        //  Import equip_info to the database
        $records = array('equip_info', 'change_record', 'relocation_record', 'mro_record');
        foreach ($records as $tablename){
            insert_records($db , $tablename);
        }

        /* Restart foreign key check */
        mysqli_query($db,'SET FOREIGN_KEY_CHECKS = 1;');

        $status = '导入成功';
    

    }
}

echo $status;

?>

<!DOCTYPE html>
<html>
<body>

<?php include('navi.php');?>

<h1>租赁设备管理系统</h1>

<h2> 数据导入 </h2>


<?php echo $status;

if ($status == '导入成功'){
    ?>
<a href = 'index.php'> 返回主页 </a>
<?php
}
else {
?>

如果文档已经上传，请在此处输入文件名后点"读取文档"。
<div>
    <form action = "load.php" method = "post">
    
    <dl>
        <dt> 文档名 </dt>
        <dd> <input type = "text" name = "filename" value = "" /></dd>
    </dl>

    <input type = "submit" name = "submit"  value = "读取文档"/>

    </form>
</div>

<?php } ?>

</body>
</html>
