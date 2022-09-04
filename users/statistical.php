<?php 
    include('../connect.php');
    include('../jwt.php');
    $res = [];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'mes'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'mes'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }
    
    $sqlSelect = "SELECT * FROM user";
    $rlSelect = mysqli_query($conn, $sqlSelect);
    $num = mysqli_num_rows($rlSelect);
    
    array_push($res, ['error'=>0, 'statistical'=>$num]);
    echo json_encode($res);
?>