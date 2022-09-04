<?php 
include('../../connect.php');
include('../../jwt.php');
    $res = [];
    $id = $_GET['_id'];
    $status = $_GET['_status'];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'messages'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'messages'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }
    
    if ($id == '') {
        array_push($res, ['error'=> 1, 'messages'=> 'không tìm thấy id sản phẩm, bạn hãy tải lại trang']);
        echo json_encode($res);
        die();
    }

    if ($status == '') {
        array_push($res, ['error'=> 1, 'messages'=> 'không tìm thấy trạng thái cần thay đổi, bạn hãy tải lại trang']);
        echo json_encode($res);
        die();
    }

    $sqlUpdateStatus = "UPDATE product SET pro_status='$status' WHERE pro_id = '$id'";
    $rlUpdateStatus = mysqli_query($conn, $sqlUpdateStatus);

    array_push($res, ['error'=> 0, 'messages'=> 'Thay đổi trạng thái thành công !']);

    echo json_encode($res);

?>