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

    $sql = "SELECT * FROM user WHERE user_id = ".$verify['user']['user_id'];
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);

    if($num <=0 ){
        array_push($res, ['error'=>1, 'message'=>'Tài khoản không tồn tại']);
        echo json_encode($res);
        die();
    }

    $sql2 = "SELECT * FROM user 
            INNER JOIN city ON user.city_id = city.city_id 
            INNER JOIN user_level ON user.user_level = user_level.lv_id 
            INNER JOIN district ON user.district_id = district.district_id 
            INNER JOIN commune ON user.commune_id = commune.commune_id 
            WHERE user_id = ".$verify['user']['user_id'];
    $rl2 = mysqli_query($conn, $sql2);
    $data = mysqli_fetch_assoc($rl2);
    
    array_push($res, [
        'error'=>0, 
        'user'=>[
            'user_id'=>$data['user_id'],
            'city_id'=>$data['city_id'],
            'city_name'=>$data['city_name'],
            'district_id'=>$data['district_id'],
            'district_name'=>$data['district_name'],
            'commune_id'=>$data['commune_id'],
            'commune_name'=>$data['commune_name'],
            'user_name'=>$data['user_name'],
            'user_email'=>$data['user_email'],
            'user_phone'=>$data['user_phone'],
            'user_address'=>$data['user_address'],
            'user_status'=>$data['user_status'],
            'user_level_id'=>$data['lv_id'],
            'user_level_name'=>$data['lv_name'],
            'user_avatar'=>$data['user_avatar'],
            'user_created_at'=>$data['user_created_at'],
            'user_updated_at'=>$data['user_updated_at'],
            'baseURLImg' => URLImgUser()
        ]
    ]);
    echo json_encode($res);
    
?>