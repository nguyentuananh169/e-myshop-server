<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $pro_id=$_POST['_pro_id'];
    $parent_id=$_POST['_parent_id'];
    $content=$_POST['_content'];

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

    $sqlInsert = "INSERT INTO comments(pro_id, user_id, cmt_parent_id, cmt_content) VALUES('$pro_id','$user_id','$parent_id','$content')";
    $rlInsert = mysqli_query($conn, $sqlInsert);
 
    $insert_id = mysqli_insert_id($conn);

    if($insert_id > 0){
        array_push($res, ['error'=>0, 'message'=>'Cảm ơn bạn đã bình luận sản phẩm']);
        echo json_encode($res);
        die();
    }else{
        array_push($res, ['error'=>1, 'message'=>'Bình luận thất bại']);
        echo json_encode($res);
        die();
    }
    
?>