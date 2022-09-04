<?php 
    include('../connect.php');
    include('../jwt.php');
    include('../library.php');
    $res=[];
    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $idUser = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$idUser'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'message'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
    }else{
        $data = mysqli_fetch_assoc($rl);
        array_push($res, [
            'error' => 0,
            'user' => [
                'user_id'=>$data['user_id'],
                'user_name'=>$data['user_name'],
                'user_email'=>$data['user_email'],
                'user_avatar'=>$data['user_avatar'],
                'user_phone'=>$data['user_phone'],
                'baseURLImg' => URLImgUser(), 
            ],
            'auth'=> true,
            'admin' => $data['user_level'] > 1 ? true : false
        ]);
        echo json_encode($res);
    }
?>