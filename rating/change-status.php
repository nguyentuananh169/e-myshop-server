<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $id=$_GET['_id'];

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

    if($id == ''){
        array_push($res, ['error'=>1, 'message'=>'Không thấy id dữ liệu! Hãy tải lại trang']);
        echo json_encode($res);
        die();
    }

    $sql = "SELECT * FROM rating WHERE r_id = '$id'";
    $rl = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($rl);
    $status = $data['r_status'] == 0 ? 1: 0;

    $sqlUpdate = "UPDATE rating SET r_status = '$status' WHERE r_id = '$id'";
    $rlUpdate = mysqli_query($conn, $sqlUpdate);

    array_push($res, ['error'=>0, 'message'=>'Thay đổi trạng thái thành công']);
    echo json_encode($res);
?>