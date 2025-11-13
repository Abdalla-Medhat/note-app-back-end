<?php
//delete old image <<DOI>>

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../funs.php';

$imgName = requestFilter("imgName");

if (!empty($imgName)&& $imgName != "null") {
    deleteImage("../images", $imgName);
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "image not found"]);
}
?>