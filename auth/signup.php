<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection.php';

$username = requestFilter('username');
$password = requestFilter('password');
$email = requestFilter('email');

$stmt = $con->prepare("INSERT INTO users(`username`,`password`,`email`)
VALUES(?,?,?)");
$stmt->execute([$username,$password,$email]);

$status = $stmt->rowCount();

if($status > 0){
    echo json_encode(array("status" => "success"));
}else{
    echo json_encode(array("status" => "failed"));
}
?>