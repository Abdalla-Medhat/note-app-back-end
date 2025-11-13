<?php

//Post a Clean Request from the user
function requestFilter($request){
    return htmlspecialchars(strip_tags($_POST[$request]));
} 

//Upload image function
function uploadImg($request){
    //if request is not set or image is not uploaded return Null
    if (!isset($_FILES[$request])|| $_FILES[$request]['error'] === UPLOAD_ERR_NO_FILE){
        return;
    }
    // This number is 1024 * 1024 which is 1MB
    define("MB", 1048576);
    global $errormsg;
    $imageName = uniqid() . "_" . $_FILES[$request]['name'];
    $imageSize = $_FILES[$request]['size'];
    $imageTemp = $_FILES[$request]['tmp_name'];
    $imageType = strtolower($_FILES[$request]['type']);
    $imageError = $_FILES[$request]['error'];
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    $tranlated = explode('.', $imageName);
    $extension = end($tranlated);
    
    //check the possible errors
    if ($imageError === UPLOAD_ERR_INI_SIZE) {
        $errormsg[] = 'File exceeds upload_max_filesize limit in php.ini';
    } elseif ($imageError === UPLOAD_ERR_FORM_SIZE) {
        $errormsg[] = 'File exceeds MAX_FILE_SIZE limit in HTML form';
    } elseif ($imageError !== UPLOAD_ERR_OK) {
        $errormsg[] = 'Unknown upload error (code ' . $imageError . ')';
    }
    
    //Check if image size is more than 2MB
    if($imageSize > 2 * MB){
        $errormsg[] = 'Image size is more than 2MB';
    }
    //Check if image type is valid
    if (!in_array($extension, $allowedTypes) && !empty($imageName)) {
        
        $errormsg[] = 'Invalid image type';
    }
    // if there are no errors
    if(empty($errormsg)){
        move_uploaded_file($imageTemp, "../images/".$imageName);
        return $imageName;
    }else{
        // if there are errors
        return array("status" => "failed", "error" => $errormsg);
    }
}

//Image Deletion function
function deleteImage($dir, $imgName){
    if(file_exists($dir . "/" . $imgName )){
        unlink($dir . "/" . $imgName);
    }
}

//getting the values from env file
// 1) تحميل مكتبة dotenv (تأكد إنك ثبتت composer و vlucas/phpdotenv)
require __DIR__ . '/vendor/autoload.php';

// 2) تحميل ملف .env (في مثالنا .env فوق مجلد html)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 3) اقرأ القيم المطلوبة من البيئة
$expectedUser = $_ENV['API_USER'] ?? null;
$expectedPass = $_ENV['API_PASS_HASH'] ?? null;

// 4) تحقق من وجود الإعدادات الأساسية
if (!$expectedUser || !$expectedPass) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => 'Server misconfigured.']);
    exit;
}

//check if the user is authenticated
function checkAuthenticate(){
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        global $expectedUser;
        global $expectedPass;
        $usernameInput = $_SERVER['PHP_AUTH_USER'];
        $passwordInput = $_SERVER['PHP_AUTH_PW'];
        if(!hash_equals($expectedUser, $usernameInput) || !password_verify($passwordInput, $expectedPass)){
            header('WWW-Authenticate: Basic realm="My Realm"');
            http_response_code(401);
            echo json_encode(['status'=>'error','message'=>'Invalid credentials']);
            exit;
        }
        }else {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
            exit;
        

    }

}
?>