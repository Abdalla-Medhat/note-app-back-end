<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../connection.php';

$email = requestFilter('email');
$password = requestFilter('password');

$stmt = $con->prepare("SELECT * FROM users WHERE `email`=? AND `password`=?");
$stmt->execute([$email,$password]);
$data= $stmt->fetch((PDO::FETCH_ASSOC));

$status = $stmt->rowCount();

if($status > 0){
    echo json_encode(array("status" => "success", "data" => $data));
}else{
    echo json_encode(array("status" => "failed"));
}
?>