<?php
$res = new stdClass();
$res -> deleted=false;
$res -> message='';
try{
	$rawdata = file_get_contents("php://input");
	$data = json_decode($rawdata);
	$db = new PDO("sqlite:Base de datos/database.db");
	$db -> exec('PRAGMA foreign_keys = ON;');
	$sql = $db -> prepare('delete from actividades where id=?;');
	if($sql){
		$sql -> execute(array($data->id));
		if($sql -> rowCount() > 0){
			$res -> deleted=true;
		}
	}
}catch(Exception $exc){
	$res -> message=$exc->getMessage();
}
header('Content-type: application/json');
echo json_encode($res);