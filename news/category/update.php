<?php 
    include('../../connect.php');
    include('../../jwt.php');

    $res = [];
    $id = $_POST['_id'];
    $name = trim($_POST['_name']);
    $status = $_POST['_status'];

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

    if ($id == '' || $name == '' || $status == '') {
        array_push($res, ['error'=> 1,'mes'=> 'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }

    $sqlCheckName = "SELECT * FROM category_news WHERE cate_name='$name' AND cate_id != '$id'";
    $rlCheckName = mysqli_query($conn, $sqlCheckName);
    $num = mysqli_num_rows($rlCheckName);

    if ($num > 0) {
        array_push($res, ['error'=> 1,'mes'=> 'Tên danh mục đã tồn tại. Vui lòng nhập lại tên danh mục !']);
        echo json_encode($res);
        die();
    }

    $sqlUpdate = "UPDATE category_news SET cate_name='$name', cate_status='$status' WHERE cate_id='$id'";
    $rlUpdate = mysqli_query($conn, $sqlUpdate);

    array_push($res, ['error'=> 0,'mes'=> 'Xửa danh mục thành công !']);
    echo json_encode($res);
?>