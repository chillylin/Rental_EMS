<?php 

$path = './csv/';
$filename = 'codedefinition.csv';
$pf = $path.$filename;

function read_csv($file){

	$return_array = array();


	if (($handle = fopen($file, "r")) !== FALSE) {
		
		while (($data = fgetcsv($handle, 300, ",")) !== FALSE) {
			array_push($return_array, $data);
		}
		fclose($handle);
	}

	return $return_array;
}

print_r (read_csv($pf));

?>
