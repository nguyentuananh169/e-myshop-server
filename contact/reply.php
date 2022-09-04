<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $id=$_POST['_id'];
    $reply=$_POST['_reply'];

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
        die();
    }
    $dataUser = mysqli_fetch_assoc($rl);
    if($id == '' || $reply == ''){
        array_push($res, ['error'=>1, 'message'=>'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }
    

    $sql = "INSERT INTO contact(c_name, c_email, c_parent_id, c_content) VALUES('".$dataUser['user_name']."', '".$dataUser['user_email']."', '$id', '$reply')";
    $rl = mysqli_query($conn, $sql);
 
    $insert_id = mysqli_insert_id($conn);

    if($insert_id > 0){
        $sqlUpdate = "UPDATE contact SET c_status='1' WHERE c_id='$id'";
        $rlUpdate = mysqli_query($conn, $sqlUpdate);
        array_push($res, ['error'=>0, 'message'=>'Gửi nội dung thành công']);
        echo json_encode($res);
        die();
    }else{
        array_push($res, ['error'=>1, 'message'=>'Gửi nội dung thất bại']);
        echo json_encode($res);
        die();
    }
    
?>