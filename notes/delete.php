<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection.php';

$note_id = requestFilter('id');
$imgName = requestFilter("imgName");

$stmt = $con->prepare("DELETE FROM `notes` WHERE `note_id`= ?");
$stmt->execute([$note_id]);

$status = $stmt->rowCount();

if($status > 0){
    if (!empty($imgName)&& $imgName != "null") {
    deleteImage("../images", $imgName);
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "image not found"]);
}
    
}else{
    echo json_encode(array("status" => "failed"));
}
?>