<?php 
    include('../connect.php');
    include('../jwt.php');
    include('../headers.php');
    $res = [];

    $id = $_GET['_id'];
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
        array_push($res, ['error'=>1, 'message'=>'Chỉ có quản trị viên mới được quyền xóa']);
        echo json_encode($res);
        die();
    }

    if ($id == '') {
        array_push($res, ['error'=>1, 'message'=>'Dữ liệu trống. Bạn hãy tải lại trang và thử lại']);
        echo json_encode($res);
        die();
    }

    $sql = "DELETE FROM user WHERE user_id = '$id'";
    $rl = mysqli_query($conn, $sql);
    
    $sqlCheck = "SELECT * FROM user WHERE user_id = '$id'";
    $rlCheck = mysqli_query($conn, $sqlCheck);
    $check = mysqli_num_rows($rlCheck);
    if ($check > 0) {
        array_push($res, ['error'=>1, 'message'=>'Xóa người dùng thất bại']);
        echo json_encode($res);
    }else{
        unlink('../images/user/'.$data['pro_img']);
        array_push($res, ['error'=>0, 'message'=>'Xóa người dùng thành công']);
        echo json_encode($res);
    }

?>