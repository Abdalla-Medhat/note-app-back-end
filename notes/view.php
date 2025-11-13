<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection.php';

$id = requestFilter('id');

$stmt = $con->prepare("SELECT * FROM notes WHERE `user_note`=?");
$stmt->execute([$id]);
$data= $stmt->fetchAll((PDO::FETCH_ASSOC));

$status = $stmt->rowCount(); 

if($status > 0){
    echo json_encode(array("status" => "success", "data" => $data));
}else{
    echo json_encode(array("status" => "failed"));
}
?>
