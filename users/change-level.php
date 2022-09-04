<?php 
include('../connect.php');
include('../jwt.php');
    $res = [];
    $id = $_POST['_id'];
    $level = $_POST['_level'];

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }

    $sqlCheckLevel = "SELECT * FROM user WHERE user_id = ".$verify['user']['user_id'];
    $rlCheckLevel = mysqli_query($conn, $sqlCheckLevel);
    $checkLevel = mysqli_fetch_assoc($rlCheckLevel);

    if ($checkLevel['user_level'] != 3) {
        array_push($res, ['error'=>1, 'message'=>'Chỉ có quản trị viên mới được quyền thay đổi']);
        echo json_encode($res);
        die();
    }

    if ($id == '' || $level == '') {
        array_push($res, ['error'=>1, 'message'=>'Dữ liệu trống. Bạn hãy tải lại trang và thử lại']);
        echo json_encode($res);
        die();
    }

    $sql = "UPDATE user SET user_level = '$level' WHERE user_id = '$id' ";
    $rl = mysqli_query($conn, $sql);

    array_push($res, ['error'=> 0, 'message'=> 'Thay đổi chức vụ thành công !']);

    echo json_encode($res);

?>