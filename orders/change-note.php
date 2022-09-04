<?php 
    include('../connect.php');
    include('../jwt.php');
    $res=[];
    $order_id = $_GET['_order_id'];
    $order_note = $_GET['_order_note'];

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
    $data = mysqli_fetch_assoc($rl);
    if($data['user_level'] < 3){
        array_push($res, ['error'=>1, 'message'=>'Chỉ có quản trị viên mới được quyền thay đổi']);
        echo json_encode($res);
        die();
    }

    $sql = "UPDATE orders SET order_admin_note = '$order_note' WHERE order_id = '$order_id'";
    $rl = mysqli_query($conn, $sql);

    array_push($res, ['error'=>0, 'message'=>'Gửi nội dung thành công']);
    echo json_encode($res);
    die();
?>