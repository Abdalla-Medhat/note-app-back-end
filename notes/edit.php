<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection.php';

$note_id = requestFilter('id');
$title = requestFilter('note_title');
$content = requestFilter('note_content');
$imgName = uploadImg("image");

if (!empty($imgName)&& $imgName != "null"){
    $stmt = $con->prepare("UPDATE `notes` SET `note_title`= ?, `note_content`= ? , `note_img`= ? WHERE `note_id`= ?");
    $stmt->execute([$title, $content,$imgName , $note_id]);
}else{ $stmt = $con->prepare("UPDATE `notes` SET `note_title`= ?, `note_content`= ? WHERE `note_id`= ?");
$stmt->execute([$title,$content,$note_id]);
}

$status = $stmt->rowCount();

if($status > 0){
    echo json_encode(["status" => "success"]);
} 
else{echo json_encode(array("status" => "failed"));
}
?> 
