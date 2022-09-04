<?php 
include('../../connect.php');
include('../../jwt.php');
    $res = [];
    $id = $_GET['_id'] ;

    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'mes'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $user_id = $verify['user']['user_id'];
    $sql = "SELECT * FROM user WHERE user_id='$user_id'";
    $rl = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($rl);
    if($num <= 0){
        array_push($res, ['error'=>1, 'mes'=>'Tài không tồn tại trong hệ thống']);
        echo json_encode($res);
        die();
    }

    if ($id == '') {
        array_push($res, ['error'=> 1, 'mes'=> 'Lỗi! id danh mục trống']);
        echo json_encode($res);
        die();
    }

    $sql = "SELECT * FROM category_news WHERE cate_id = '$id'";
    $rl = mysqli_query($conn, $sql);

    $data = mysqli_fetch_assoc($rl);
    $dataStatus = $data['cate_status'] == 0 ? 1 : 0;

    $sqlUpdateStatus = "UPDATE category_news SET cate_status='$dataStatus' WHERE cate_id = '$id'";
    $rlUpdateStatus = mysqli_query($conn, $sqlUpdateStatus);

    array_push($res, ['error'=> 0, 'mes'=> 'Thay đổi trạng thái thành công !']);

    echo json_encode($res);

?>