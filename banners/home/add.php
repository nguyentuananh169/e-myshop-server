<?php 
    include('../../connect.php');
    include('../../jwt.php');
    $res = [];

    $img = $_FILES['_img'];
    $link = $_POST['_link'];
    $headers = apache_request_headers();
    $token = $headers['access_token'];
    $token = str_replace('Bearer ', '', $token);
    $verify = verifyAccessToken($token);
    if ($verify['err']) {
        array_push($res, ['error'=>1, 'message'=>$verify['msg']]);
        echo json_encode($res);
        die();
    }
    $time = time();

    $sqlCheckUser = "SELECT * FROM user WHERE user_id = ".$verify['user']['user_id'];
    $rlCheckUser = mysqli_query($conn, $sqlCheckUser);
    $checkUser = mysqli_num_rows($rlCheckUser);
    if ($checkUser <= 0) {
        array_push($res, ['error'=>1, 'message'=>'Không thể thực hiện. Bạn hãy tải lại trang và thử lại']);
        echo json_encode($res);
        die();
    }

    if ($img['name'] == '' || $link == '') {
        array_push($res, ['error'=>1, 'message'=>'Bạn chưa nhập đủ thông tin']);
        echo json_encode($res);
        die();
    }
    if ($img['type'] != 'image/png' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif') {
        array_push($res, ['error'=> 1, 'message'=> 'Hình ảnh bạn nhập không đúng định dạng (PNG, JPEG, GIF)']);
        echo json_encode($res);
        die();
    }

    $sql = "INSERT INTO banner_home(bh_img, bh_link) VALUES('".$time.$img['name']."', '$link')";
    $rl = mysqli_query($conn, $sql);
    
    $insert_id = mysqli_insert_id($conn);
    if ($insert_id > 0) {
        move_uploaded_file($img['tmp_name'], '../../images/banner/'.$time.$img['name']);
        array_push($res, ['error'=>0, 'message'=>'Thêm mới thành công']);
        echo json_encode($res);
    }else{
        unlink('../images/user/'.$data['pro_img']);
        array_push($res, ['error'=>1, 'message'=>'Thêm mới thất bại']);
        echo json_encode($res);
    }

?>