<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $pro_id=$_POST['_pro_id'];
    $star=$_POST['_star'];
    $content=$_POST['_content'];
    $parent_id=$_POST['_parent_id'];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'message'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }
    if ($parent_id==0) {
        $sqlCheck = "SELECT * FROM rating WHERE pro_id='$pro_id' AND user_id='$user_id' AND r_parent_id='0'";
        $rlCheck = mysqli_query($conn, $sqlCheck);
        $num = mysqli_num_rows($rlCheck);
        if ($num > 0) {
            array_push($res, ['error'=>1, 'message'=>'Bạn đã đánh giá sản phẩm này rồi']);
            echo json_encode($res);
            die();
        }
    }
    
    $sqlInsert = "INSERT INTO rating(pro_id, user_id, r_content, r_star, r_parent_id) VALUES('$pro_id','$user_id','$content','$star','$parent_id')";
    $rlInsert = mysqli_query($conn, $sqlInsert);
 
    $insert_id = mysqli_insert_id($conn);

    if($insert_id > 0){
        array_push($res, ['error'=>0, 'message'=>'Cảm ơn bạn đã đánh giá sản phẩm']);
        echo json_encode($res);
        die();
    }else{
        array_push($res, ['error'=>1, 'message'=>'Đánh giá thất bại, Lỗi server']);
        echo json_encode($res);
        die();
    }
    
?>