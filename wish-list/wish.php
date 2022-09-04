<?php 
include('../connect.php');
include('../jwt.php');
    $res = [];

    $pro_id = $_GET['_id'];

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

    if ($pro_id == '') {
        array_push($res, ['error'=> 1, 'message'=> 'Lỗi! Bạn hãy tải lại trang và thử lại']);
        echo json_encode($res);
        die();
    }

    $sqlSelect = "SELECT * FROM product_wish WHERE pro_id = '$pro_id' AND user_id = '$user_id'";
    $rlSelect = mysqli_query($conn, $sqlSelect);
    $num = mysqli_num_rows($rlSelect);

    if ($num > 0) {
        $sqlDelete = "DELETE FROM product_wish WHERE pro_id = '$pro_id' AND user_id = '$user_id'";
        $rlDelete = mysqli_query($conn, $sqlDelete);
        array_push($res, ['error'=> 0, 'message'=> 'Đã xóa khỏi danh sách yêu thích']);
        echo json_encode($res);
    }else{
        $sqlInsert = "INSERT INTO product_wish(pro_id, user_id) VALUES('$pro_id', '$user_id')";
        $rlInsert = mysqli_query($conn, $sqlInsert);
        array_push($res, ['error'=> 0, 'message'=> 'Đã thêm vào danh sách yêu thích']);
        echo json_encode($res);
    }
?>