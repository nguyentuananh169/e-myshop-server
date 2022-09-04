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
    // Lấy hình ảnh 
    $sqlSelect = "SELECT * FROM news WHERE news_id='$id'";
    $rlSelect = mysqli_query($conn, $sqlSelect);
    $data = mysqli_fetch_assoc($rlSelect);
    // Xóa tin tức
    $sqlDelete = "DELETE FROM news WHERE news_id='$id'";
    $rlDelete = mysqli_query($conn, $sqlDelete);
    // ktra xem còn trong db không
    $sqlCheck = "SELECT * FROM news WHERE news_id='$id'";
    $rlCheck = mysqli_query($conn, $sqlCheck);
    $num2 = mysqli_num_rows($rlCheck);
    if ($num2 > 0) {
        array_push($res, ['error'=> 1,'mes'=> 'Xóa thất bại !']);
        echo json_encode($res);
    }else{
        unlink('../../images/news/'.$data['news_img']);
        array_push($res, ['error'=> 0,'mes'=> 'Xóa thành công !']);
        echo json_encode($res);
    }
?>